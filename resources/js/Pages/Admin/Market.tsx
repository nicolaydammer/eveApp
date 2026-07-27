import { useEffect, useMemo, useState } from "react";
import { usePage } from "@inertiajs/react";
import {
    ChevronsLeft,
    ChevronsRight,
    Plus,
    Trash2,
} from "lucide-react";

import AppLayout from "@/Layouts/AppLayout.js";
import ThemeToggle from "@/Components/ThemeToggle.js";

import {
    getExistingRegionConfiguration,
    getExistingStructureConfiguration,
    Region,
    StructureMarketConfiguration,
} from "@/admin/adminMarket.js";

interface Character {
    CharacterID: number;
    CharacterName: string;
}

interface Auth {
    user: {
        id: number;
        main_character_id: number;
        characters: Character[];
        is_admin: boolean;
    };
}

export default function Market() {
    return (
        <AppLayout>
            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">
                        Market Settings
                    </h1>

                    <ThemeToggle />
                </div>

                <div className="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    <RegionMarketSettings />
                    <StructureMarketSettings />
                </div>
            </div>
        </AppLayout>
    );
}

/*
|--------------------------------------------------------------------------
| Region Market Settings
|--------------------------------------------------------------------------
*/

function RegionMarketSettings() {
    const [regions, setRegions] = useState<Region[]>([]);
    const [synced, setSynced] = useState<number[]>([]);

    const [selectedAvailable, setSelectedAvailable] = useState<number[]>([]);
    const [selectedSynced, setSelectedSynced] = useState<number[]>([]);

    const [loading, setLoading] = useState(true);

    useEffect(() => {
        getExistingRegionConfiguration()
            .then((data) => {
                setRegions(data.regions);
                setSynced(data.synced);
            })
            .finally(() => setLoading(false));
    }, []);

    const availableRegions = useMemo(
        () => regions.filter((region) => !synced.includes(region.id)),
        [regions, synced]
    );

    const syncedRegions = useMemo(
        () => regions.filter((region) => synced.includes(region.id)),
        [regions, synced]
    );

    const moveRight = () => {
        if (selectedAvailable.length === 0) {
            return;
        }

        setSynced((current) => [
            ...new Set([
                ...current,
                ...selectedAvailable,
            ]),
        ]);

        setSelectedAvailable([]);

        // TODO: Persist configuration.
    };

    const moveLeft = () => {
        if (selectedSynced.length === 0) {
            return;
        }

        setSynced((current) =>
            current.filter(
                (id) => !selectedSynced.includes(id)
            )
        );

        setSelectedSynced([]);

        // TODO: Persist configuration.
    };

    if (loading) {
        return (
            <div className="border border-zinc-800 rounded-lg p-5">
                Loading regions...
            </div>
        );
    }

    return (
        <div className="border border-zinc-800 rounded-lg">
            <div className="p-4 border-b border-zinc-800">
                <h2 className="font-semibold">
                    Region Market Sync
                </h2>

                <p className="text-sm text-zinc-400 mt-1">
                    Select which regions should have their market orders
                    synchronized.
                </p>
            </div>

            <div className="p-4 grid grid-cols-[1fr_auto_1fr] gap-4 items-center">
                <RegionList
                    title="Available"
                    regions={availableRegions}
                    selected={selectedAvailable}
                    setSelected={setSelectedAvailable}
                />

                <div className="flex flex-col gap-3">
                    <button
                        type="button"
                        onClick={moveRight}
                        disabled={selectedAvailable.length === 0}
                        className="
                            p-2 rounded border border-zinc-700
                            hover:bg-zinc-800
                            disabled:opacity-30
                            disabled:cursor-not-allowed
                        "
                    >
                        <ChevronsRight size={20} />
                    </button>

                    <button
                        type="button"
                        onClick={moveLeft}
                        disabled={selectedSynced.length === 0}
                        className="
                            p-2 rounded border border-zinc-700
                            hover:bg-zinc-800
                            disabled:opacity-30
                            disabled:cursor-not-allowed
                        "
                    >
                        <ChevronsLeft size={20} />
                    </button>
                </div>

                <RegionList
                    title="Synchronized"
                    regions={syncedRegions}
                    selected={selectedSynced}
                    setSelected={setSelectedSynced}
                />
            </div>
        </div>
    );
}

function RegionList({
    title,
    regions,
    selected,
    setSelected,
}: {
    title: string;
    regions: Region[];
    selected: number[];
    setSelected: (ids: number[]) => void;
}) {
    const select = (
        regionId: number,
        multiSelect: boolean
    ) => {
        if (!multiSelect) {
            setSelected([regionId]);
            return;
        }

        if (selected.includes(regionId)) {
            setSelected(
                selected.filter((id) => id !== regionId)
            );

            return;
        }

        setSelected([
            ...selected,
            regionId,
        ]);
    };

    return (
        <div>
            <h3 className="text-sm font-medium mb-2">
                {title}
            </h3>

            <div className="h-80 overflow-y-auto border border-zinc-800 rounded bg-zinc-950 p-1">
                {regions.map((region) => {
                    const isSelected = selected.includes(region.id);

                    return (
                        <button
                            key={region.id}
                            type="button"
                            onClick={(event) =>
                                select(
                                    region.id,
                                    event.ctrlKey || event.metaKey
                                )
                            }
                            className={`
                                w-full text-left px-3 py-2 rounded text-sm
                                ${isSelected
                                    ? "bg-zinc-700"
                                    : "hover:bg-zinc-900"
                                }
                            `}
                        >
                            <div>
                                {region.name}
                            </div>

                            <div className="text-xs text-zinc-500">
                                {region.id}
                            </div>
                        </button>
                    );
                })}

                {regions.length === 0 && (
                    <div className="h-full flex items-center justify-center text-sm text-zinc-500">
                        Empty
                    </div>
                )}
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Structure Market Settings
|--------------------------------------------------------------------------
*/

function StructureMarketSettings() {
    const { auth } = usePage().props as unknown as {
        auth: Auth;
    };

    const characters = auth.user.characters;

    const [mappings, setMappings] = useState<StructureMarketConfiguration[]>([]);
    const [selectedMapping, setSelectedMapping] =
        useState<StructureMarketConfiguration | null>(null);

    const [structureId, setStructureId] = useState("");
    const [characterId, setCharacterId] = useState("");

    const [loading, setLoading] = useState(true);

    useEffect(() => {
        getExistingStructureConfiguration()
            .then(setMappings)
            .finally(() => setLoading(false));
    }, []);

    const addMapping = () => {
        if (!structureId || !characterId) {
            return;
        }

        const character = characters.find(
            (character) =>
                character.CharacterID === Number(characterId)
        );

        if (!character) {
            return;
        }

        const mapping: StructureMarketConfiguration = {
            structure_id: Number(structureId),
            character_id: character.CharacterID,
            character_name: character.CharacterName,
        };

        setMappings((current) => [
            ...current,
            mapping,
        ]);

        setStructureId("");
        setCharacterId("");

        // TODO: Persist configuration.
    };

    const removeMapping = () => {
        if (!selectedMapping) {
            return;
        }

        setMappings((current) =>
            current.filter(
                (mapping) =>
                    !(
                        mapping.structure_id === selectedMapping.structure_id &&
                        mapping.character_id === selectedMapping.character_id
                    )
            )
        );

        setSelectedMapping(null);

        // TODO: Persist configuration.
    };

    if (loading) {
        return (
            <div className="border border-zinc-800 rounded-lg p-5">
                Loading structures...
            </div>
        );
    }

    return (
        <div className="border border-zinc-800 rounded-lg">
            <div className="p-4 border-b border-zinc-800">
                <h2 className="font-semibold">
                    Structure Market Sync
                </h2>

                <p className="text-sm text-zinc-400 mt-1">
                    Configure structures and which of your characters should
                    synchronize them.
                </p>
            </div>

            <div className="p-4 space-y-5">
                <div>
                    <h3 className="text-sm font-medium mb-2">
                        Configured Structures
                    </h3>

                    <div className="border border-zinc-800 rounded overflow-hidden">
                        <div className="grid grid-cols-2 px-3 py-2 bg-zinc-900 text-sm font-semibold">
                            <div>Structure ID</div>
                            <div>Character</div>
                        </div>

                        <div className="h-56 overflow-y-auto bg-zinc-950">
                            {mappings.map((mapping) => {
                                const isSelected =
                                    selectedMapping?.structure_id ===
                                    mapping.structure_id &&
                                    selectedMapping?.character_id ===
                                    mapping.character_id;

                                return (
                                    <button
                                        key={`${mapping.structure_id}-${mapping.character_id}`}
                                        type="button"
                                        onClick={() =>
                                            setSelectedMapping(
                                                isSelected
                                                    ? null
                                                    : mapping
                                            )
                                        }
                                        className={`
                                            grid grid-cols-2
                                            w-full text-left
                                            px-3 py-2 text-sm
                                            border-t border-zinc-900
                                            ${isSelected
                                                ? "bg-zinc-700"
                                                : "hover:bg-zinc-900"
                                            }
                                        `}
                                    >
                                        <div>
                                            {mapping.structure_id}
                                        </div>

                                        <div>
                                            {mapping.character_name}

                                            <span className="text-zinc-500 ml-2">
                                                ({mapping.character_id})
                                            </span>
                                        </div>
                                    </button>
                                );
                            })}

                            {mappings.length === 0 && (
                                <div className="h-full flex items-center justify-center text-sm text-zinc-500">
                                    No structures configured
                                </div>
                            )}
                        </div>
                    </div>

                    <div className="flex justify-end mt-3">
                        <button
                            type="button"
                            onClick={removeMapping}
                            disabled={!selectedMapping}
                            className="
                                flex items-center gap-2
                                px-3 py-2 rounded
                                border border-zinc-700
                                text-sm text-red-400
                                hover:bg-red-900/20
                                disabled:opacity-30
                                disabled:cursor-not-allowed
                            "
                        >
                            <Trash2 size={16} />
                            Remove
                        </button>
                    </div>
                </div>

                <div className="border-t border-zinc-800 pt-4">
                    <h3 className="text-sm font-medium mb-3">
                        Add Structure
                    </h3>

                    <div className="space-y-3">
                        <div>
                            <label className="block text-sm mb-1">
                                Structure ID
                            </label>

                            <input
                                type="text"
                                inputMode="numeric"
                                value={structureId}
                                onChange={(event) =>
                                    setStructureId(
                                        event.target.value.replace(/\D/g, "")
                                    )
                                }
                                placeholder="Structure ID"
                                className="
                                    w-full px-3 py-2 rounded
                                    border border-zinc-700
                                    bg-zinc-950
                                    text-sm
                                    outline-none
                                    focus:border-zinc-500
                                "
                            />
                        </div>

                        <div>
                            <label className="block text-sm mb-1">
                                Character
                            </label>

                            <select
                                value={characterId}
                                onChange={(event) =>
                                    setCharacterId(event.target.value)
                                }
                                className="
                                    w-full px-3 py-2 rounded
                                    border border-zinc-700
                                    bg-zinc-950
                                    text-sm
                                    outline-none
                                    focus:border-zinc-500
                                "
                            >
                                <option value="">
                                    Select character
                                </option>

                                {characters.map((character) => (
                                    <option
                                        key={character.CharacterID}
                                        value={character.CharacterID}
                                    >
                                        {character.CharacterName} (
                                        {character.CharacterID})
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div className="flex justify-end">
                            <button
                                type="button"
                                onClick={addMapping}
                                disabled={!structureId || !characterId}
                                className="
                                    flex items-center gap-2
                                    px-3 py-2 rounded
                                    border border-zinc-700
                                    text-sm
                                    hover:bg-zinc-800
                                    disabled:opacity-30
                                    disabled:cursor-not-allowed
                                "
                            >
                                <Plus size={16} />
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
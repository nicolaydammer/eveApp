import { useEffect, useMemo, useState } from "react";
import {
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight,
} from "lucide-react";
import { Input } from "@/Components/ui/input.js";

import AppLayout from "@/Layouts/AppLayout.js";
import ThemeToggle from "@/Components/ThemeToggle.js";

import {
    getScopes,
    getConfiguration,
    saveConfiguration,
    Scope,
} from "@/admin/adminScopes.js";

export default function AdminScopes() {
    const [scopes, setScopes] = useState<Scope[]>([]);
    const [enabledScopes, setEnabledScopes] = useState<string[]>([]);

    const [selectedAvailable, setSelectedAvailable] = useState<string[]>([]);
    const [selectedEnabled, setSelectedEnabled] = useState<string[]>([]);

    const [availableAnchor, setAvailableAnchor] = useState<string | null>(null);
    const [enabledAnchor, setEnabledAnchor] = useState<string | null>(null);

    const [availableSearch, setAvailableSearch] = useState("");
    const [enabledSearch, setEnabledSearch] = useState("");

    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);

    useEffect(() => {
        Promise.all([getScopes(), getConfiguration()])
            .then(([scopes, configuration]) => {
                setScopes(scopes);
                setEnabledScopes(configuration);
            })
            .finally(() => setLoading(false));
    }, []);

    const availableScopes = useMemo(() => {
        const search = availableSearch.toLowerCase();

        return scopes.filter(
            (scope) =>
                !enabledScopes.includes(scope) &&
                scope.toLowerCase().includes(search)
        );
    }, [scopes, enabledScopes, availableSearch]);

    const enabledScopeItems = useMemo(() => {
        const search = enabledSearch.toLowerCase();

        return scopes.filter(
            (scope) =>
                enabledScopes.includes(scope) &&
                scope.toLowerCase().includes(search)
        );
    }, [scopes, enabledScopes, enabledSearch]);

    const updateConfiguration = async (configuration: string[]) => {
        setEnabledScopes(configuration);
        setSaving(true);

        try {
            await saveConfiguration(configuration);
        } catch (error) {
            console.error("Failed to save ESI scope configuration", error);
        } finally {
            setSaving(false);
        }
    };

    const moveSelectedRight = () => {
        if (selectedAvailable.length === 0) return;

        updateConfiguration([
            ...new Set([...enabledScopes, ...selectedAvailable]),
        ]);
        setSelectedAvailable([]);
        setAvailableAnchor(null);
    };

    const moveAllRight = () => {
        updateConfiguration(scopes.map((scope) => scope));
        setSelectedAvailable([]);
        setAvailableAnchor(null);
    };

    const moveSelectedLeft = () => {
        if (selectedEnabled.length === 0) return;

        updateConfiguration(
            enabledScopes.filter(
                (scope) => !selectedEnabled.includes(scope)
            )
        );
        setSelectedEnabled([]);
        setEnabledAnchor(null);
    };

    const moveAllLeft = () => {
        updateConfiguration([]);
        setSelectedEnabled([]);
        setEnabledAnchor(null);
    };

    const toggleSelection = (
        value: string,
        visibleScopes: string[],
        selected: string[],
        setSelected: React.Dispatch<React.SetStateAction<string[]>>,
        anchor: string | null,
        setAnchor: React.Dispatch<React.SetStateAction<string | null>>,
        event: React.MouseEvent<HTMLButtonElement>
    ) => {
        const index = visibleScopes.indexOf(value);

        if (index === -1) {
            return;
        }

        // Shift-click selects the complete range from the last anchor.
        if (event.shiftKey && anchor !== null) {
            const anchorIndex = visibleScopes.indexOf(anchor);

            if (anchorIndex !== -1) {
                const start = Math.min(anchorIndex, index);
                const end = Math.max(anchorIndex, index);

                setSelected(visibleScopes.slice(start, end + 1));
                return;
            }
        }

        // Ctrl/Cmd-click toggles an individual item and makes it the
        // new anchor for a subsequent Shift-click.
        if (event.ctrlKey || event.metaKey) {
            setSelected((current) =>
                current.includes(value)
                    ? current.filter((item) => item !== value)
                    : [...current, value]
            );
            setAnchor(value);
            return;
        }

        // Normal click selects one item and makes it the anchor.
        setSelected([value]);
        setAnchor(value);
    };

    return (
        <AppLayout>
            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <h1></h1>

                    <ThemeToggle />
                </div>

                <div className="flex justify-center">
                    <div className="w-full max-w-6xl border border-zinc-800 rounded-xl overflow-hidden">
                        <div className="p-4 border-b border-zinc-800">
                            <div className="flex items-center justify-between">
                                <div>
                                    <h2 className="text-lg font-semibold">
                                        Application Scopes
                                    </h2>
                                    <p className="text-sm text-zinc-400 mt-1">
                                        Configure which ESI scopes should be
                                        requested during EVE SSO authorization.
                                    </p>
                                </div>

                                {saving && (
                                    <span className="text-xs text-zinc-500">
                                        Saving...
                                    </span>
                                )}
                            </div>
                        </div>

                        {loading ? (
                            <div className="h-[540px] flex items-center justify-center">
                                <span className="text-sm text-zinc-500">
                                    Loading scopes...
                                </span>
                            </div>
                        ) : (
                            <div className="p-4">
                                <div className="grid grid-cols-1 lg:grid-cols-[1fr_auto_1fr] gap-4">
                                    <ScopeList
                                        title="Available"
                                        scopes={availableScopes}
                                        selected={selectedAvailable}
                                        search={availableSearch}
                                        onSearchChange={setAvailableSearch}
                                        onSelect={(value, event) =>
                                            toggleSelection(
                                                value,
                                                availableScopes,
                                                selectedAvailable,
                                                setSelectedAvailable,
                                                availableAnchor,
                                                setAvailableAnchor,
                                                event
                                            )
                                        }
                                    />

                                    <div className="flex lg:flex-col justify-center items-center gap-2">
                                        <ScopeButton
                                            icon={<ChevronRight size={18} />}
                                            title="Move selected right"
                                            disabled={!selectedAvailable.length}
                                            onClick={moveSelectedRight}
                                        />
                                        <ScopeButton
                                            icon={<ChevronsRight size={18} />}
                                            title="Move all right"
                                            disabled={!availableScopes.length}
                                            onClick={moveAllRight}
                                        />
                                        <ScopeButton
                                            icon={<ChevronLeft size={18} />}
                                            title="Move selected left"
                                            disabled={!selectedEnabled.length}
                                            onClick={moveSelectedLeft}
                                        />
                                        <ScopeButton
                                            icon={<ChevronsLeft size={18} />}
                                            title="Move all left"
                                            disabled={!enabledScopes.length}
                                            onClick={moveAllLeft}
                                        />
                                    </div>

                                    <ScopeList
                                        title="Enabled"
                                        scopes={enabledScopeItems}
                                        selected={selectedEnabled}
                                        search={enabledSearch}
                                        onSearchChange={setEnabledSearch}
                                        onSelect={(value, event) =>
                                            toggleSelection(
                                                value,
                                                enabledScopeItems,
                                                selectedEnabled,
                                                setSelectedEnabled,
                                                enabledAnchor,
                                                setEnabledAnchor,
                                                event
                                            )
                                        }
                                    />
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}

function ScopeButton({
    icon,
    title,
    disabled,
    onClick,
}: {
    icon: React.ReactNode;
    title: string;
    disabled: boolean;
    onClick: () => void;
}) {
    return (
        <button
            type="button"
            title={title}
            disabled={disabled}
            onClick={onClick}
            className="w-9 h-9 flex items-center justify-center rounded-md border border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-100 disabled:opacity-30 disabled:hover:bg-transparent disabled:hover:text-zinc-400 transition"
        >
            {icon}
        </button>
    );
}

function ScopeList({
    title,
    scopes,
    selected,
    search,
    onSearchChange,
    onSelect,
}: {
    title: string;
    scopes: Scope[];
    selected: string[];
    search: string;
    onSearchChange: (value: string) => void;
    onSelect: (
        value: string,
        event: React.MouseEvent<HTMLButtonElement>
    ) => void;
}) {
    return (
        <div className="min-w-0">
            <div className="flex items-center justify-between mb-2">
                <h3 className="text-sm font-semibold">{title}</h3>
                <span className="text-xs text-zinc-500">{scopes.length}</span>
            </div>

            <Input
                type="text"
                value={search}
                onChange={(event) => onSearchChange(event.target.value)}
                placeholder="Search scopes..."
                className="mb-2"
            />

            <div className="h-[420px] overflow-y-auto border border-zinc-800 rounded-md p-2">
                {scopes.length === 0 ? (
                    <div className="h-full flex items-center justify-center text-sm text-zinc-500">
                        No scopes found
                    </div>
                ) : (
                    <div className="space-y-1">
                        {scopes.map((scope) => {
                            const isSelected = selected.includes(scope);

                            return (
                                <button
                                    key={scope}
                                    type="button"
                                    onClick={(event) =>
                                        onSelect(scope, event)
                                    }
                                    className={`w-full text-left rounded-md px-3 py-2 transition ${isSelected
                                        ? "bg-zinc-800 text-zinc-100"
                                        : "text-zinc-300 hover:bg-zinc-900"
                                        }`}
                                >
                                    <div className="text-sm truncate">
                                        {scope}
                                    </div>
                                </button>
                            );
                        })}
                    </div>
                )}
            </div>
        </div>
    );
}
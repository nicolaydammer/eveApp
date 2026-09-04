import { useEffect, useState } from 'react';

import BlueprintSearch from './BlueprintSearch.js';
import SelectField from './SelectField.js';
import {
    getRigs,
    getStructures,
    getIndustryActivities,
} from '../api/industryApi.js';
import type { IndustrySettings } from '../types/IndustrySettings.js';
import type { IndustryActivity } from '../types/IndustryActivity.js';
import type { Rig, Structure } from '../types/Structure.js';
import SystemSearch from './SystemSearch.js';

interface Props {
    open: boolean;
    settings: IndustrySettings;
    onApply: (settings: IndustrySettings) => void;
    onClose: () => void;
}

export default function SettingsModal({
    open,
    settings,
    onApply,
    onClose,
}: Props) {
    const [draft, setDraft] = useState(settings);
    const [structures, setStructures] = useState<Structure[]>([]);
    const [rigs, setRigs] = useState<Rig[]>([]);
    const [loadingStructures, setLoadingStructures] = useState(false);
    const [loadingRigs, setLoadingRigs] = useState(false);
    const [activities, setActivities] = useState<IndustryActivity[]>([]);

    useEffect(() => {
        if (!open) return;

        setDraft(settings);
    }, [open, settings]);

    useEffect(() => {
        void getIndustryActivities().then(setActivities);
    }, []);

    /*
     * Load structures for the selected activity.
     */
    useEffect(() => {
        if (!open || !draft.activity) {
            setStructures([]);
            return;
        }

        setLoadingStructures(true);

        void getStructures(draft.activity._key)
            .then((items) => {
                setStructures(items);

                if (
                    draft.structure &&
                    !items.some(
                        (item) =>
                            item._key === draft.structure?._key,
                    )
                ) {
                    setDraft((current) => ({
                        ...current,
                        structure: null,
                        rigs: [],
                    }));
                }
            })
            .finally(() => setLoadingStructures(false));
    }, [
        open,
        draft.activity?._key,
    ]);

    /*
     * Load rigs for the selected structure and activity.
     */
    useEffect(() => {
        if (!open || !draft.activity || !draft.structure) {
            setRigs([]);
            return;
        }

        setLoadingRigs(true);

        void getRigs(
            draft.structure._key,
            draft.activity._key,
        )
            .then((items) => {
                setRigs(items);

                setDraft((current) => ({
                    ...current,
                    rigs: current.rigs.map((selectedRig) =>
                        selectedRig &&
                            items.some(
                                (rig) =>
                                    rig._key === selectedRig._key,
                            )
                            ? selectedRig
                            : null,
                    ),
                }));
            })
            .finally(() => setLoadingRigs(false));
    }, [
        open,
        draft.activity?._key,
        draft.structure?._key,
    ]);

    if (!open) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div className="w-full max-w-2xl rounded-xl border border-zinc-200 bg-white p-6 shadow-xl dark:border-zinc-800 dark:bg-zinc-950">
                <div className="mb-6 flex items-start justify-between">
                    <div>
                        <h2 className="text-lg font-semibold">
                            Industry settings
                        </h2>

                        <p className="mt-1 text-sm text-zinc-500">
                            Select the context used by the calculator.
                        </p>
                    </div>

                    <button
                        type="button"
                        onClick={onClose}
                        className="text-zinc-500 hover:text-zinc-900 dark:hover:text-white"
                    >
                        ×
                    </button>
                </div>

                <div className="space-y-5">
                    <BlueprintSearch
                        value={draft.blueprint}
                        onChange={(blueprint) =>
                            setDraft((current) => ({
                                ...current,
                                blueprint,
                            }))
                        }
                    />

                    <SelectField
                        label="Activity"
                        value={draft.activity?._key ?? ''}
                        placeholder="Select activity"
                        options={activities.map((activity) => ({
                            value: activity._key,
                            label: activity.name,
                        }))}
                        onChange={(value) => {
                            const activity = activities.find(
                                (activity) =>
                                    activity._key === Number(value),
                            );

                            if (!activity) return;

                            setDraft((current) => ({
                                ...current,
                                activity,
                                structure: null,
                                rigs: [],
                            }));
                        }}
                    />

                    <div className="grid gap-4 sm:grid-cols-2">
                        <SelectField
                            label="Structure"
                            value={draft.structure?._key ?? ''}
                            disabled={
                                !draft.activity ||
                                loadingStructures
                            }
                            placeholder={
                                loadingStructures
                                    ? 'Loading...'
                                    : 'Select structure'
                            }
                            options={structures.map((structure) => ({
                                value: structure._key,
                                label: structure.name,
                            }))}
                            onChange={(value) => {
                                const structure =
                                    structures.find(
                                        (item) =>
                                            item._key ===
                                            Number(value),
                                    ) ?? null;

                                setDraft((current) => ({
                                    ...current,
                                    structure,
                                    rigs: [],
                                }));
                            }}
                        />

                        <div>
                            <label className="mb-2 block text-sm font-medium">
                                Rigs
                            </label>

                            {!draft.structure ? (
                                <p className="text-sm text-gray-500">
                                    Select a structure first.
                                </p>
                            ) : loadingRigs ? (
                                <p className="text-sm text-gray-500">
                                    Loading rigs...
                                </p>
                            ) : rigs.length === 0 ? (
                                <p className="text-sm text-gray-500">
                                    No compatible rigs available.
                                </p>
                            ) : (
                                <div className="space-y-2">
                                    {Array.from({
                                        length: draft.structure.rigSlots,
                                    }).map((_, slot) => {
                                        const selectedRig =
                                            draft.rigs[slot] ?? null;

                                        const options = [
                                            {
                                                value: '',
                                                label: 'None',
                                            },
                                            ...rigs
                                                .filter((rig) => {
                                                    /*
                                                     * A rig kind can only be
                                                     * selected once.
                                                     *
                                                     * T1/T2 variants share
                                                     * groupID, so selecting
                                                     * one hides the other
                                                     * variants in the other
                                                     * slots.
                                                     */
                                                    return !draft.rigs.some(
                                                        (
                                                            selected,
                                                            selectedSlot,
                                                        ) =>
                                                            selectedSlot !==
                                                            slot &&
                                                            selected?.groupID ===
                                                            rig.groupID,
                                                    );
                                                })
                                                .map((rig) => ({
                                                    value: rig._key,
                                                    label: rig.name,
                                                })),
                                        ];

                                        return (
                                            <SelectField
                                                key={slot}
                                                label={`Rig ${slot + 1}`}
                                                value={
                                                    selectedRig?._key ??
                                                    ''
                                                }
                                                options={options}
                                                onChange={(value) => {
                                                    const rig =
                                                        rigs.find(
                                                            (item) =>
                                                                item._key ===
                                                                Number(
                                                                    value,
                                                                ),
                                                        ) ?? null;

                                                    setDraft((current) => {
                                                        const nextRigs = [
                                                            ...current.rigs,
                                                        ];

                                                        nextRigs[slot] =
                                                            rig;

                                                        return {
                                                            ...current,
                                                            rigs: nextRigs,
                                                        };
                                                    });
                                                }}
                                            />
                                        );
                                    })}
                                </div>
                            )}
                        </div>
                    </div>

                    <SystemSearch
                        value={draft.system}
                        onChange={(system) =>
                            setDraft((current) => ({
                                ...current,
                                system,
                            }))
                        }
                    />

                    {draft.activity &&
                        [1, 3, 4].includes(draft.activity._key) && (
                            <div className="grid gap-4 sm:grid-cols-2">
                                <NumberInput
                                    label="Material Efficiency"
                                    value={draft.materialEfficiency}
                                    onChange={(value) =>
                                        setDraft((current) => ({
                                            ...current,
                                            materialEfficiency: value,
                                        }))
                                    }
                                    limit={10}
                                />

                                <NumberInput
                                    label="Time Efficiency"
                                    value={draft.timeEfficiency}
                                    onChange={(value) =>
                                        setDraft((current) => ({
                                            ...current,
                                            timeEfficiency: value,
                                        }))
                                    }
                                    limit={20}
                                />
                            </div>
                        )}
                </div>

                <div className="mt-8 flex justify-end gap-3">
                    <button
                        type="button"
                        onClick={onClose}
                        className="rounded-md border border-zinc-300 px-4 py-2 text-sm dark:border-zinc-700"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        disabled={!draft.blueprint || !draft.activity}
                        onClick={() => onApply(draft)}
                        className="rounded-md bg-zinc-900 px-4 py-2 text-sm text-white disabled:opacity-50 dark:bg-white dark:text-zinc-900"
                    >
                        Apply
                    </button>
                </div>
            </div>
        </div>
    );
}

function NumberInput({
    label,
    value,
    onChange,
    limit
}: {
    label: string;
    value: number;
    onChange: (value: number) => void;
    limit: number
}) {
    return (
        <div className="space-y-2">
            <label className="text-sm font-medium">{label}</label>

            <input
                type="number"
                min={0}
                max={limit}
                value={value}
                onChange={(event) =>
                    onChange(Number(event.target.value))
                }
                className="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-900"
            />
        </div>
    );
}
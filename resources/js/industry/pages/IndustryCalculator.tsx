import { useEffect, useState } from 'react';

import ActivityPlaceholder from '../components/ActivityPlaceholder.js';
import ManufacturingCalculator from '../components/ManufacturingCalculator.js';
import SettingsModal from '../components/SettingsModal.js';
import { getIndustryData } from '../api/industryApi.js';
import type { IndustrySettings } from '../types/IndustrySettings.js';
import type { Rig } from '../types/Structure.js';

const ACTIVITY_MANUFACTURING = 1;

const initialSettings: IndustrySettings = {
    blueprint: null,
    activity: null,
    structure: null,
    rigs: [],
    system: null,
    materialEfficiency: 0,
    timeEfficiency: 0,
};

export default function IndustryCalculator() {
    const [settings, setSettings] = useState(initialSettings);
    const [settingsOpen, setSettingsOpen] = useState(true);
    const [data, setData] = useState<unknown>(null);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (!settings.blueprint || !settings.activity) {
            setData(null);
            return;
        }

        const blueprintId = settings.blueprint._key;
        const activity = settings.activity;

        const timeout = window.setTimeout(async () => {
            // setLoading(true);

            // try {
            //     const result = await getIndustryData(
            //         blueprintId,
            //         activity,
            //         {
            //             structureId: settings.structure?._key ?? null,
            //             rigIds: settings.rigs
            //                 .filter(
            //                     (rig): rig is Rig => rig !== null,
            //                 )
            //                 .map((rig) => rig._key),
            //             systemId: settings.system?._key ?? null,
            //             materialEfficiency: settings.materialEfficiency,
            //             timeEfficiency: settings.timeEfficiency,
            //         },
            //     );

            //     setData(result);
            // } finally {
            //     setLoading(false);
            // }
        }, 400);

        return () => window.clearTimeout(timeout);
    }, [settings]);

    return (
        <>
            <main className="mx-auto max-w-7xl space-y-6 p-6">
                <header className="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-semibold">
                            Industry Calculator
                        </h1>

                        <p className="mt-1 text-sm text-zinc-500">
                            Calculate industry jobs from your selected context.
                        </p>
                    </div>

                    <button
                        type="button"
                        onClick={() => setSettingsOpen(true)}
                        className="rounded-md border border-zinc-300 px-4 py-2 text-sm dark:border-zinc-700"
                    >
                        Settings
                    </button>
                </header>

                <div className="grid gap-4 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-950 sm:grid-cols-2 lg:grid-cols-5">
                    <Summary
                        label="Blueprint"
                        value={settings.blueprint?.name ?? '—'}
                    />

                    <Summary
                        label="Activity"
                        value={settings.activity?.name ?? '—'}
                    />

                    <Summary
                        label="Structure"
                        value={settings.structure?.name ?? '—'}
                    />

                    <Summary
                        label="Rigs"
                        value={
                            settings.rigs
                                .filter(
                                    (rig): rig is Rig => rig !== null,
                                )
                                .map((rig) => rig.name)
                                .join(', ') || '—'
                        }
                    />

                    <Summary
                        label="System"
                        value={
                            settings.system
                                ? `${settings.system.system} (${settings.system.securityStatus.toFixed(1)})`
                                : '—'
                        }
                    />
                </div>

                {loading && (
                    <p className="text-sm text-zinc-500">
                        Loading calculation data...
                    </p>
                )}

                {!settings.blueprint || !settings.activity ? (
                    <section className="rounded-xl border border-dashed border-zinc-300 p-10 text-center dark:border-zinc-700">
                        <h2 className="font-medium">
                            Select a blueprint and activity to begin
                        </h2>

                        <p className="mt-1 text-sm text-zinc-500">
                            Open Settings and select the blueprint and industry
                            context.
                        </p>
                    </section>
                ) : settings.activity._key === ACTIVITY_MANUFACTURING ? (
                    <ManufacturingCalculator
                        settings={settings}
                        data={data}
                    />
                ) : (
                    <ActivityPlaceholder settings={settings} />
                )}
            </main>

            <SettingsModal
                open={settingsOpen}
                settings={settings}
                onApply={(nextSettings) => {
                    setSettings(nextSettings);
                    setSettingsOpen(false);
                }}
                onClose={() => setSettingsOpen(false)}
            />
        </>
    );
}

function Summary({
    label,
    value,
}: {
    label: string;
    value: string;
}) {
    return (
        <div>
            <div className="text-xs text-zinc-500">{label}</div>

            <div className="mt-1 truncate font-medium capitalize">
                {value}
            </div>
        </div>
    );
}
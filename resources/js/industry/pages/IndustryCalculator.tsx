import { useEffect, useState } from 'react';

import ActivityPlaceholder from '../components/ActivityPlaceholder.js';
import ManufacturingCalculator from '../components/ManufacturingCalculator.js';
import SettingsModal from '../components/SettingsModal.js';
import { marketData, blueprintTree, modifiersData, systemCostIndex, referencePrices } from '../api/industryApi.js';
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
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (!settings.blueprint || !settings.activity) {
            return;
        }

        const blueprintId = settings.blueprint._key;
        const activity = settings.activity;

        const timeout = window.setTimeout(async () => {
            setLoading(true);

            try {

                // market data (structure, region, reference prices)
                const market = marketData();
                //referencePrices
                const reference = referencePrices();
                // tree
                const tree = blueprintTree(settings.blueprint._key);
                // modifiers
                let rigIds: number[] = [];
                settings.rigs.forEach(element => {
                    rigIds.push(element?._key);
                });
                const modifiers = modifiersData(settings.system?.securityStatus, settings.activity?.name, rigIds);
                // industry cost indices
                const indices = systemCostIndex(settings.system?._key);

                // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! calculate prices as last so ME is calculated!

                // tab 1: full tree, where node show full price determined by material costs which are based on the child nodes.
                // child nodes are configured in tab 2 where you can determine the settings per blueprint.
                // we need to be able to make decisions on either we buy something or build it ourselfs.
                // default build everything
                // you need material market prices and if something has a schema then derive cost from child material nodes and keep repeating that
                // if something is a buy from market then directly show market price for that material and cut the tree there.

                // tab 2: list all schemas and configure them by setting activity to either reaction or manufacturing, system, structure and rigs.
                // interactively have a list of market default ie <type_id> = market: c-j6, default: true so we know it can be calculated again which market to default to.
                // default configuration for a schema is ME/TE: 0/0, modifiers: null, system: null, rigs: null


                // we need to be able to save the entire state to the database on any momement notice.
                // depending on performance we can make it automatically sync with a debounce or have to hit the save button.
                // in case of a auto sync feature, we still need a manual sync button.


                // total volume: unit_volume * quantity
                // me: check modifier data for which is me and apply that if the group id of the node is right.
                // te: same as above but for te

                // direct buy: total price = market[<market_name>][typeID] * quantity
                // build yourself: total price = formula.total_price * quantity

                // total_price is derived data.
                // It must be recalculated when:
                // - market data changes
                // - build/buy decisions change
                // - material market selection changes
                // - blueprint ME/TE changes
                // - structure/rig/system/modifier settings change
                // - relevant SDE/calculation data changes

                //Retrieve inputs
                // │
                // ├── market data
                // ├── tree
                // ├── modifiers
                // └── industry cost indices
                //         │
                //         ▼
                //    Calculate tree
                //         │
                //         ├── quantities
                //         ├── ME
                //         ├── TE
                //         ├── modifiers
                //         ├── build/buy decisions
                //         │
                //         ▼
                //    Calculate prices LAST
                //         │
                //         └── total_price

            } finally {
                setLoading(false);
            }
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
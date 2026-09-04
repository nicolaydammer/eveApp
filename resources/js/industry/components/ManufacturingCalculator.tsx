import type { IndustrySettings } from '../types/IndustrySettings.js';

interface Props {
    settings: IndustrySettings;
    data: unknown;
}

export default function ManufacturingCalculator({
    settings,
    data,
}: Props) {
    return (
        <section className="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-950">
            <h2 className="text-lg font-semibold">Manufacturing</h2>

            <p className="mt-1 text-sm text-zinc-500">
                Manufacturing calculation will be implemented here.
            </p>

            <div className="mt-6 grid gap-4 sm:grid-cols-3">
                <Value label="Material Efficiency" value={settings.materialEfficiency} />
                <Value label="Time Efficiency" value={settings.timeEfficiency} />
                <Value
                    label="Security"
                    value={settings.system?.security ?? '—'}
                />
            </div>

            <pre className="mt-6 overflow-auto rounded-lg bg-zinc-100 p-4 text-xs dark:bg-zinc-900">
                {JSON.stringify(data, null, 2)}
            </pre>
        </section>
    );
}

function Value({
    label,
    value,
}: {
    label: string;
    value: number | string;
}) {
    return (
        <div>
            <div className="text-xs text-zinc-500">{label}</div>
            <div className="mt-1 font-medium">{value}</div>
        </div>
    );
}

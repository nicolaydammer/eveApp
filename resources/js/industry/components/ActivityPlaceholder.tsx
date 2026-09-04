import type { IndustrySettings } from '../types/IndustrySettings.js';

export default function ActivityPlaceholder({
    settings,
}: {
    settings: IndustrySettings;
}) {
    return (
        <section className="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-950">
            <h2 className="text-lg font-semibold">
                {settings.activity.replace('_', ' ')}
            </h2>

            <p className="mt-1 text-sm text-zinc-500">
                This activity is not implemented yet.
            </p>
        </section>
    );
}

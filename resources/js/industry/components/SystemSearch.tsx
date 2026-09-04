import { useEffect, useState } from 'react';

import { getSystems } from '../api/industryApi.js';
import type { System } from '../types/System.js';

interface Props {
    value: System | null;
    onChange: (system: System | null) => void;
}

export default function SystemSearch({
    value,
    onChange,
}: Props) {
    const [search, setSearch] = useState(value?.system ?? '');
    const [results, setResults] = useState<System[]>([]);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (search.trim().length < 2 || search === value?.system) {
            setResults([]);
            return;
        }

        const timeout = window.setTimeout(async () => {
            setLoading(true);

            try {
                const systems = await getSystems(search);
                setResults(systems);
            } finally {
                setLoading(false);
            }
        }, 300);

        return () => window.clearTimeout(timeout);
    }, [search, value?.system]);

    return (
        <div className="relative">
            <label className="mb-2 block text-sm font-medium">
                System
            </label>

            <input
                type="text"
                value={search}
                placeholder="Search for a system..."
                onChange={(event) => {
                    setSearch(event.target.value);

                    if (value) {
                        onChange(null);
                    }
                }}
                className="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm outline-none focus:border-zinc-500 dark:border-zinc-700 dark:bg-zinc-900"
            />

            {loading && (
                <div className="mt-1 text-xs text-zinc-500">
                    Searching...
                </div>
            )}

            {!loading && results.length > 0 && (
                <div className="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
                    {results.map((system) => (
                        <button
                            key={system._key}
                            type="button"
                            onClick={() => {
                                onChange(system);
                                setSearch(system.system);
                                setResults([]);
                            }}
                            className="block w-full px-3 py-2 text-left text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800"
                        >
                            <div className="font-medium">
                                {system.system}
                            </div>

                            <div className="text-xs text-zinc-500">
                                Security:{' '}
                                {system.securityStatus.toFixed(2)}
                            </div>
                        </button>
                    ))}
                </div>
            )}

            {!loading &&
                search.trim().length >= 2 &&
                search !== value?.system &&
                results.length === 0 && (
                    <div className="mt-1 text-xs text-zinc-500">
                        No systems found.
                    </div>
                )}
        </div>
    );
}
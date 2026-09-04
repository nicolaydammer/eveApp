import { useEffect, useState } from 'react';

import { searchBlueprints } from '../api/industryApi.js';
import type { Blueprint } from '../types/Blueprint.js';

interface Props {
    value: Blueprint | null;
    onChange: (blueprint: Blueprint | null) => void;
}

export default function BlueprintSearch({ value, onChange }: Props) {
    const [query, setQuery] = useState(value?.name ?? '');
    const [results, setResults] = useState<Blueprint[]>([]);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        // Don't search again when the query is the currently selected blueprint.
        if (value && query === value.name) {
            setResults([]);
            return;
        }

        const timeout = window.setTimeout(async () => {
            if (query.trim().length < 2) {
                setResults([]);
                return;
            }

            setLoading(true);

            try {
                setResults(await searchBlueprints(query));
            } finally {
                setLoading(false);
            }
        }, 300);

        return () => window.clearTimeout(timeout);
    }, [query, value]);

    return (
        <div className="space-y-2">
            <label className="text-sm font-medium">Blueprint</label>

            <div className="relative">
                <input
                    type="text"
                    value={query}
                    placeholder="Search blueprint..."
                    onChange={(event) => {
                        const nextQuery = event.target.value;

                        setQuery(nextQuery);
                        setResults([]);

                        if (value) {
                            onChange(null);
                        }
                    }}
                    className="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm outline-none focus:border-zinc-500 dark:border-zinc-700 dark:bg-zinc-900"
                />

                {results?.length > 0 && (
                    <div className="absolute z-10 mt-1 w-full overflow-hidden rounded-md border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
                        {results.map((blueprint) => (
                            <button
                                key={blueprint._key}
                                type="button"
                                className="block w-full px-3 py-2 text-left text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800"
                                onClick={() => {
                                    setQuery(blueprint.name);
                                    setResults([]);
                                    onChange(blueprint);
                                }}
                            >
                                {blueprint.name}
                            </button>
                        ))}
                    </div>
                )}
            </div>

            {loading && (
                <p className="text-xs text-zinc-500">Searching...</p>
            )}
        </div>
    );
}
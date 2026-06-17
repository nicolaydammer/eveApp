import React, { useState, useEffect } from 'react';
import { useForm, router } from '@inertiajs/react';
import AppLayout from "@/Layouts/AppLayout.js";
import IndustryCalculator from "@/industry/IndustryCalculator.js";

export default function Industry({ results = [] }) {
    const { data, setData } = useForm({
        blueprint_key: '',
    });

    const [search, setSearch] = useState('');
    const [loading, setLoading] = useState(false);
    const [selectedBlueprint, setSelectedBlueprint] = useState(null);

    // Inertia handle for search bar ONLY
    useEffect(() => {
        if (!search.trim()) {
            router.get(window.location.pathname, { search: '' }, {
                preserveState: true,
                only: ['results']
            });
            return;
        }

        setLoading(true);

        const delayDebounce = setTimeout(() => {
            router.get(
                window.location.pathname,
                { search: search },
                {
                    preserveState: true,
                    preserveScroll: true,
                    only: ['results'],
                    onFinish: () => setLoading(false)
                }
            );
        }, 250);

        return () => clearTimeout(delayDebounce);
    }, [search]);

    const handleSelect = (blueprint) => {
        setSelectedBlueprint(blueprint);
        setData('blueprint_key', blueprint._key);
        setSearch('');
    };

    const handleClear = () => {
        setData('blueprint_key', '');
        setSelectedBlueprint(null);
    };

    const cleanResults = Array.isArray(results) ? results : [];

    return (
        <AppLayout>
            <div className="p-6 space-y-6">

                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">EVE Dashboard</h1>

                    {/* Snap target blueprint to top right upon selection */}
                    {selectedBlueprint && (
                        <div className="w-80 bg-gray-900 text-white p-3 rounded-lg border border-gray-700 shadow-md flex items-center justify-between">
                            <div className="truncate pr-2">
                                <p className="text-xs text-gray-400 font-medium uppercase tracking-wider">Target Blueprint</p>
                                <p className="text-sm font-semibold truncate text-indigo-400">{selectedBlueprint.name}</p>
                            </div>
                            <button
                                type="button"
                                onClick={handleClear}
                                className="px-2.5 py-1 text-xs font-medium text-white bg-gray-800 hover:bg-gray-700 rounded-md border border-gray-600 transition-colors"
                            >
                                Change
                            </button>
                        </div>
                    )}
                </div>

                <div className="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                    <div className="flex gap-3">
                        {/* Space left intentionally for header row controls */}
                    </div>
                </div>

                {/* Search Bar Input State */}
                {!selectedBlueprint && (
                    <div className="w-full max-w-md mx-auto p-6 bg-gray-50 rounded-xl border border-gray-200 shadow-sm">
                        <label className="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            Target Blueprint
                        </label>

                        <div className="relative">
                            <div className="relative flex items-center">
                                <input
                                    type="text"
                                    className="w-full border border-gray-300 rounded-lg pl-3 pr-10 py-2.5 bg-white text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition-all shadow-sm"
                                    placeholder="Type blueprint name..."
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    autoFocus
                                />
                                {loading && (
                                    <div className="absolute right-3 top-3">
                                        <svg className="animate-spin h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                        </svg>
                                    </div>
                                )}
                            </div>

                            {search.trim().length > 0 && (
                                <div className="absolute z-50 mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-xl max-h-64 overflow-y-auto divide-y divide-gray-100">
                                    {cleanResults.length === 0 && !loading ? (
                                        <div className="p-4 text-center text-sm text-gray-500">
                                            No blueprints match "{search}"
                                        </div>
                                    ) : (
                                        cleanResults.map((blueprint) => (
                                            <button
                                                key={blueprint._key}
                                                type="button"
                                                onClick={() => handleSelect(blueprint)}
                                                className="w-full text-left px-4 py-3 hover:bg-indigo-600 hover:text-white transition-colors flex flex-col group"
                                            >
                                                <span className="text-sm font-medium text-gray-900 group-hover:text-white">
                                                    {blueprint.name}
                                                </span>
                                            </button>
                                        ))
                                    )}
                                </div>
                            )}
                        </div>
                    </div>
                )}

                {selectedBlueprint && (
                    <IndustryCalculator
                        blueprintKey={selectedBlueprint._key}
                    />
                )}
            </div>
        </AppLayout>
    );
}
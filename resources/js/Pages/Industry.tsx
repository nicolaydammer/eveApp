import React, { useState, useEffect } from 'react';
import { useForm, router } from '@inertiajs/react';
import AppLayout from "@/Layouts/AppLayout.js";

export default function Industry({ results = [] }) {
    // Form data tracking for the blueprint selection
    const { data, setData } = useForm({
        blueprint_key: '',
    });

    const [search, setSearch] = useState('');
    const [loading, setLoading] = useState(false);
    const [selectedBlueprint, setSelectedBlueprint] = useState(null);

    // Debounced Native Inertia Route Request
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
                    preserveState: true,  // Keeps component fields from clearing out
                    preserveScroll: true, // Prevents sudden window jumps
                    only: ['results'],    // Tells Laravel to ONLY fire the lazy results method
                    onFinish: () => setLoading(false)
                }
            );
        }, 250);

        return () => clearTimeout(delayDebounce);
    }, [search]);

    const handleSelect = (blueprint) => {
        setData('blueprint_key', blueprint._key);
        setSelectedBlueprint(blueprint);
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

                </div>

                <div className="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                    <div className="flex gap-3">

                    </div>

                </div>


                <div className="w-full max-w-md mx-auto p-6 bg-gray-50 rounded-xl border border-gray-200 shadow-sm">
                    <label className="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                        Target Blueprint
                    </label>

                    {/* STATE 1: Blueprint Selected */}
                    {selectedBlueprint ? (
                        <div className="flex items-center justify-between p-3 bg-indigo-50 border border-indigo-200 rounded-lg shadow-sm">
                            <div>
                                <p className="text-sm font-semibold text-indigo-900">
                                    {selectedBlueprint.name}
                                </p>
                            </div>
                            <button
                                type="button"
                                onClick={handleClear}
                                className="px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200 rounded-md transition-colors"
                            >
                                Change
                            </button>
                        </div>
                    ) : (
                        /* STATE 2: Active Search Input */
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

                            {/* Dropdown Options overlay */}
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
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
import { useState, useEffect, useRef } from "react";
import EveCharacterHeader from "@/Components/EveCharacterHeader";
import ThemeToggle from "@/Components/ThemeToggle";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import AppLayout from "@/Layouts/AppLayout.js";
import { route } from 'ziggy-js';
import { router, Link, useForm } from "@inertiajs/react";

export default function Dashboard({ characters: initialData, filters }) {
    const [selectedId, setSelectedId] = useState(null);
    const [isProcessing, setIsProcessing] = useState(false);
    const searchInputRef = useRef(null);

    // useForm handles the search state
    const { data, setData, get } = useForm({
        search: filters?.search || "",
    });

    // Ctrl+F shortcut
    useEffect(() => {
        const handleKeyDown = (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                searchInputRef.current?.focus();
            }
        };
        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, []);

    const handleSearch = (e) => {
        e.preventDefault();
        get(route('dashboard'), {
            preserveState: true,
            replace: true,
        });
    };

    const handleSelect = (id) => {
        setSelectedId(prevId => prevId === id ? null : id);
    };

    const handleMakeMain = () => {
        if (!selectedId) return;
        setIsProcessing(true);
        router.post(route('dashboard.setMainCharacter', { CharacterID: selectedId }), {}, {
            onSuccess: () => setSelectedId(null),
            onFinish: () => setIsProcessing(false),
            preserveScroll: true
        });
    };

    return (
        <AppLayout>
            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">EVE Dashboard</h1>
                    <ThemeToggle />
                </div>

                <div className="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                    <div className="flex gap-3">
                        <Button variant="outline" onClick={() => { window.location.href = route('auth.redirectToEveSSO') }}>
                            Add alt
                        </Button>
                        <Button
                            variant="outline"
                            disabled={!selectedId || initialData.data.find(c => c.id === selectedId)?.isMain || isProcessing}
                            onClick={handleMakeMain}
                        >
                            {isProcessing ? "Updating..." : "Make Main"}
                        </Button>
                    </div>

                    <form onSubmit={handleSearch} className="flex w-full md:w-72 gap-2">
                        <div className="relative w-full">
                            <Input
                                ref={searchInputRef}
                                type="text"
                                placeholder="Search (Ctrl+F)"
                                value={data.search}
                                onChange={(e) => setData("search", e.target.value)}
                                className="bg-white dark:bg-zinc-900 pr-10"
                            />
                        </div>
                    </form>
                </div>

                {/* Grid Mapping */}
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    {initialData.data.map((char) => (
                        <div key={char.id} onClick={() => handleSelect(char.id)}>
                            <EveCharacterHeader
                                character={char}
                                isSelected={selectedId === char.id}
                            />
                        </div>
                    ))}
                </div>

                {/* Fixed Pagination Section */}
                {initialData.links.length > 3 && (
                    <div className="flex flex-wrap justify-center gap-2 mt-8">
                        {initialData.links.map((link, i) => {
                            // Helper to prevent clicking disabled/active links
                            const isDisabled = !link.url || link.active;

                            return (
                                <Link
                                    key={i}
                                    href={link.url || "#"}
                                    // Use preserveScroll to keep the user at the bottom when clicking next
                                    preserveScroll
                                    className={`
                                        px-4 py-2 text-sm rounded-md border transition-all duration-200
                                        ${link.active
                                            ? 'bg-blue-600 text-white border-blue-600'
                                            : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-800 hover:border-blue-500'}
                                        ${!link.url ? 'opacity-30 cursor-not-allowed' : 'cursor-pointer'}
                                    `}
                                    onClick={(e) => {
                                        if (isDisabled) e.preventDefault();
                                    }}
                                >
                                    <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                </Link>
                            );
                        })}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
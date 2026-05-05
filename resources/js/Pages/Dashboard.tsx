import { useState } from "react";
import EveCharacterHeader from "@/Components/EveCharacterHeader";
import ThemeToggle from "@/Components/ThemeToggle";
import { Button } from "@/Components/ui/button";
import AppLayout from "@/Layouts/AppLayout.js";
import { route } from 'ziggy-js';
import { router } from "@inertiajs/react";

export default function Dashboard({ characters: initialCharacters }) {
    const [characters, setCharacters] = useState(initialCharacters);
    const [selectedId, setSelectedId] = useState(null);
    const [isProcessing, setIsProcessing] = useState(false);

    const selectedCharacter = characters.find(c => c.id === selectedId);

    // Toggle selection: If clicking the same ID, unselect. Otherwise, select new.
    const handleSelect = (id) => {
        setSelectedId(prevId => prevId === id ? null : id);
    };

    const handleMakeMain = () => {
        if (!selectedId) return;

        setIsProcessing(true);
        router.post(route('dashboard.setMainCharacter', { CharacterID: selectedId }), {}, {
            onSuccess: () => {
                setCharacters((prev) =>
                    prev.map((c) => ({
                        ...c,
                        isMain: c.id === selectedId,
                    }))
                );
                setSelectedId(null); // Deselect after success
            },
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

                <div className="flex gap-3">
                    <Button variant="outline" onClick={() => { window.location.href = route('auth.redirectToEveSSO') }}>
                        Add alt
                    </Button>

                    {/* Matching styling with Add Alt (variant="outline") */}
                    <Button
                        variant="outline"
                        disabled={!selectedId || selectedCharacter?.isMain || isProcessing}
                        onClick={handleMakeMain}
                    >
                        {isProcessing ? "Updating..." : "Make Main"}
                    </Button>
                </div>

                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    {characters.map((char) => (
                        <div
                            key={char.id}
                            onClick={() => handleSelect(char.id)}
                            className="cursor-pointer"
                        >
                            <EveCharacterHeader
                                character={char}
                                isSelected={selectedId === char.id}
                            />
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
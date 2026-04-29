import { useState } from "react";
import EveCharacterHeader from "@/Components/EveCharacterHeader";
import ThemeToggle from "@/Components/ThemeToggle";
import { Button } from "@/Components/ui/button";
import AppLayout from "@/Layouts/AppLayout.js";

export default function Dashboard({ characters: initialCharacters }) {

    const [characters, setCharacters] = useState(initialCharacters);

    const setMain = (id) => {
        setCharacters((prev) =>
            prev.map((c) => ({
                ...c,
                isMain: c.id === id,
            }))
        );

        // In real Inertia app, replace with:
        // router.post(`/characters/${id}/set-main`)
    };

    return (
        <AppLayout>
            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">EVE Dashboard</h1>
                    <ThemeToggle />
                </div>

                <div className="grid gap-4">
                    {characters.map((char) => (
                        <div key={char.id} className="space-y-2">
                            <EveCharacterHeader character={char} />

                            <div className="flex gap-2">
                                {!char.isMain ? (
                                    <Button onClick={() => setMain(char.id)}>
                                        Set as Main
                                    </Button>
                                ) : (
                                    <Button disabled variant="secondary">
                                        Main Character
                                    </Button>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
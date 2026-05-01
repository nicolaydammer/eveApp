import { useState } from "react";
import EveCharacterHeader from "@/Components/EveCharacterHeader";
import ThemeToggle from "@/Components/ThemeToggle";
import { Button } from "@/Components/ui/button";
import AppLayout from "@/Layouts/AppLayout.js";
import { route } from 'ziggy-js';

export default function Dashboard({ characters: initialCharacters }) {

    const [characters, setCharacters] = useState(initialCharacters);

    return (
        <AppLayout>
            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">EVE Dashboard</h1>
                    <ThemeToggle />
                </div>

                <div>
                    <Button variant="" onClick={() => { window.location.href = route('auth.redirectToEveSSO') }}>Add alt</Button>
                </div>

                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    {characters.map((char) => (
                        <div key={char.id} className="space-y-2">
                            <EveCharacterHeader character={char} setCharacters={setCharacters} />
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
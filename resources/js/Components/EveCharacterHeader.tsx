import { Card, CardContent } from "@/Components/ui/card";
import { Button } from "./ui/button.js";
import { route } from "../../../vendor/tightenco/ziggy/src/js/index.js";
import { router } from "@inertiajs/react";

export default function EveCharacterHeader({ character, setCharacters }) {

    const setMain = (id) => {
        setCharacters((prev) =>
            prev.map((c) => ({
                ...c,
                isMain: c.id === id,
            }))
        );

        // In real Inertia app, replace with:
        router.post(`${route('dashboard.setMainCharacter', { CharacterID: id })}`);
    };

    return (
        <Card className="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
            <CardContent className="p-4 flex gap-4">

                {/* Character Portrait */}
                <img
                    src={character.portrait}
                    className="w-20 h-20 rounded-xl"
                />

                <div className="flex-1 flex flex-col space-y-2">

                    {/* Name */}
                    <div className="flex items-center gap-2">
                        <h2 className="text-lg font-semibold">
                            {character.name}
                        </h2>

                        {character.isMain && (
                            <span className="text-xs px-2 py-1 rounded bg-green-500/20 text-green-400">
                                MAIN
                            </span>
                        )}
                    </div>

                    {/* Corp + Alliance */}
                    <div className="flex items-center gap-3 text-sm opacity-80">

                        <img
                            src={character.corpLogo}
                            className="w-6 h-6 rounded"
                        />
                        <span>{character.corpName}</span>

                        {character.allianceName && (
                            <>
                                <span>•</span>
                                <img
                                    src={character.allianceLogo}
                                    className="w-6 h-6 rounded"
                                />
                                <span>{character.allianceName}</span>
                            </>
                        )}

                    </div>

                    <div className="mt-auto self-end">
                        {!character.isMain ? (
                            <Button onClick={() => setMain(character.id)}>
                                Set as Main
                            </Button>
                        ) : (
                            <Button disabled variant="secondary">
                                Main Character
                            </Button>
                        )}
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}
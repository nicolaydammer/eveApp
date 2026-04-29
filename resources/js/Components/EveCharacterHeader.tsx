import { Card, CardContent } from "@/Components/ui/card";

export default function EveCharacterHeader({ character }) {
    return (
        <Card className="rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
            <CardContent className="p-4 flex gap-4">

                {/* Character Portrait */}
                <img
                    src={character.portrait ?? null}
                    className="w-20 h-20 rounded-xl"
                />

                <div className="flex-1 space-y-2">

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
                            src={character.corpLogo ?? null}
                            className="w-6 h-6 rounded"
                        />
                        <span>{character.corpName}</span>

                        {character.allianceName && (
                            <>
                                <span>•</span>
                                <img
                                    src={character.allianceLogo ?? null}
                                    className="w-6 h-6 rounded"
                                />
                                <span>{character.allianceName}</span>
                            </>
                        )}

                    </div>
                </div>
            </CardContent>
        </Card>
    );
}
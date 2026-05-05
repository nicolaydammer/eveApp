import { Card, CardContent } from "@/Components/ui/card";

export default function EveCharacterHeader({ character, isSelected }) {
    return (
        <Card className={`
            rounded-2xl transition-all duration-200 bg-white dark:bg-zinc-900 
            border-2 
            ${isSelected
                ? "border-blue-500 ring-2 ring-blue-500/10 shadow-md"
                : "border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700"
            }
        `}>
            <CardContent className="p-4 flex gap-4">
                <img
                    src={character.portrait}
                    alt=""
                    className="w-20 h-20 rounded-xl"
                />

                <div className="flex-1 flex flex-col space-y-2">
                    <div className="flex items-center gap-2">
                        <h2 className="text-lg font-semibold truncate">
                            {character.name}
                        </h2>
                        {character.isMain && (
                            <span className="text-[10px] font-bold px-2 py-0.5 rounded bg-green-500/20 text-green-600 dark:text-green-400 uppercase tracking-wider">
                                Main
                            </span>
                        )}
                    </div>

                    <div className="flex items-center text-sm opacity-80">
                        {character.allianceLogo && (
                            <img src={character.allianceLogo} className="w-12 h-12 rounded" alt="Alliance" />
                        )}
                        <img src={character.corpLogo} className="w-12 h-12 rounded" alt="Corp" />
                        <span className="truncate">{character.corpName}</span>
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}
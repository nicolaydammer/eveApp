import { Card, CardContent } from "@/Components/ui/card";

export default function EveCharacterHeader({ character, isSelected }) {
    return (
        <Card className={`
            cursor-pointer rounded-2xl transition-all duration-200 bg-white dark:bg-zinc-900 
            border-2 
            ${isSelected
                ? "border-blue-500 ring-2 ring-blue-500/10 shadow-md scale-[1.02]"
                : "border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700"
            }
        `}>
            <CardContent className="p-4 flex gap-4">
                <img
                    src={character.portrait}
                    alt={character.name}
                    className="w-20 h-20 rounded-xl bg-zinc-100 dark:bg-zinc-800"
                />

                <div className="flex-1 flex flex-col space-y-2 min-w-0">
                    <div className="flex items-center gap-2">
                        <h2 className="text-md font-bold truncate">
                            {character.name}
                        </h2>
                        {character.isMain && (
                            <span className="text-[10px] font-black px-1.5 py-0.5 rounded bg-green-500/20 text-green-600 dark:text-green-400 uppercase">
                                Main
                            </span>
                        )}
                    </div>

                    <div className="flex items-center text-sm opacity-90">
                        <div className="flex -space-x-1.5">
                            {character.alliance?.logo && (
                                <img
                                    src={character.alliance.logo}
                                    className="w-12 h-12 rounded border border-white dark:border-zinc-900"
                                    alt="Alliance"
                                />
                            )}
                            <img
                                src={character.corporation.logo}
                                className="w-12 h-12 rounded border border-white dark:border-zinc-900"
                                alt="Corp"
                            />
                        </div>
                        <div className="truncate text-sm font-medium text-zinc-500 ml-2">
                            {character.corporation.name}
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}
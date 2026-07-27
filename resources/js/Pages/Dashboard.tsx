import {
    type FormEvent,
    type KeyboardEvent as ReactKeyboardEvent,
    useEffect,
    useRef,
    useState,
} from "react";
import EveCharacterHeader, { type Character } from "@/Components/EveCharacterHeader.js";
import ThemeToggle from "@/Components/ThemeToggle.js";
import { Button } from "@/Components/ui/button.js";
import { Input } from "@/Components/ui/input.js";
import AppLayout from "@/Layouts/AppLayout.js";
import { route } from "ziggy-js";
import { router, Link, useForm } from "@inertiajs/react";

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedCharacters {
    data: Character[];
    links: PaginationLink[];
}

interface DashboardFilters {
    search?: string;
}

interface DashboardProps {
    characters: PaginatedCharacters;
    filters?: DashboardFilters;
}

interface SearchForm {
    search: string;
}

export default function Dashboard({
    characters: initialData,
    filters,
}: DashboardProps) {
    const [selectedId, setSelectedId] = useState<number | null>(null);
    const [isProcessing, setIsProcessing] = useState<boolean>(false);

    const searchInputRef = useRef<HTMLInputElement | null>(null);

    const { data, setData, get } = useForm<SearchForm>({
        search: filters?.search ?? "",
    });

    // Ctrl+F shortcut
    useEffect(() => {
        const handleKeyDown = (e: KeyboardEvent): void => {
            if ((e.ctrlKey || e.metaKey) && e.key === "f") {
                e.preventDefault();
                searchInputRef.current?.focus();
            }
        };

        window.addEventListener("keydown", handleKeyDown);

        return () => {
            window.removeEventListener("keydown", handleKeyDown);
        };
    }, []);

    const handleSearch = (e: FormEvent<HTMLFormElement>): void => {
        e.preventDefault();

        get(route("dashboard"), {
            preserveState: true,
            replace: true,
        });
    };

    const handleSelect = (id: number): void => {
        setSelectedId((prevId) => (prevId === id ? null : id));
    };

    const handleMakeMain = (): void => {
        if (selectedId === null) {
            return;
        }

        setIsProcessing(true);

        router.post(
            route("dashboard.setMainCharacter", {
                CharacterID: selectedId,
            }),
            {},
            {
                onSuccess: () => setSelectedId(null),
                onFinish: () => setIsProcessing(false),
                preserveScroll: true,
            },
        );
    };

    const selectedCharacter = initialData.data.find(
        (character) => character.id === selectedId,
    );

    return (
        <AppLayout>
            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">EVE Dashboard</h1>
                    <ThemeToggle />
                </div>

                <div className="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                    <div className="flex gap-3">
                        <Button
                            variant="outline"
                            onClick={() => {
                                window.location.href = route(
                                    "auth.redirectToEveSSO",
                                );
                            }}
                        >
                            Add alt
                        </Button>

                        <Button
                            variant="outline"
                            disabled={
                                selectedId === null ||
                                selectedCharacter?.isMain === true ||
                                isProcessing
                            }
                            onClick={handleMakeMain}
                        >
                            {isProcessing ? "Updating..." : "Make Main"}
                        </Button>
                    </div>

                    <form
                        onSubmit={handleSearch}
                        className="flex w-full md:w-72 gap-2"
                    >
                        <div className="relative w-full">
                            <Input
                                ref={searchInputRef}
                                type="text"
                                placeholder="Search (Ctrl+F)"
                                value={data.search}
                                onChange={(e) =>
                                    setData("search", e.target.value)
                                }
                                className="bg-white dark:bg-zinc-900 pr-10"
                            />
                        </div>
                    </form>
                </div>

                {/* Grid Mapping */}
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    {initialData.data.map((character) => (
                        <div
                            key={character.id}
                            onClick={() => handleSelect(character.id)}
                        >
                            <EveCharacterHeader
                                character={character}
                                isSelected={selectedId === character.id}
                            />
                        </div>
                    ))}
                </div>

                {/* Fixed Pagination Section */}
                {initialData.links.length > 3 && (
                    <div className="flex flex-wrap justify-center gap-2 mt-8">
                        {initialData.links.map((link, index) => {
                            const isDisabled = !link.url || link.active;

                            return (
                                <Link
                                    key={index}
                                    href={link.url ?? "#"}
                                    preserveScroll
                                    className={`
                                        px-4 py-2 text-sm rounded-md border transition-all duration-200
                                        ${link.active
                                            ? "bg-blue-600 text-white border-blue-600"
                                            : "bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-800 hover:border-blue-500"
                                        }
                                        ${!link.url
                                            ? "opacity-30 cursor-not-allowed"
                                            : "cursor-pointer"
                                        }
                                    `}
                                    onClick={(e: React.MouseEvent<HTMLAnchorElement>) => {
                                        if (isDisabled) {
                                            e.preventDefault();
                                        }
                                    }}
                                >
                                    <span
                                        dangerouslySetInnerHTML={{
                                            __html: link.label,
                                        }}
                                    />
                                </Link>
                            );
                        })}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
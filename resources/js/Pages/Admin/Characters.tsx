import { useEffect, useState } from "react";
import { Link } from "@inertiajs/react";
import axios from "@/lib/axios.js";
import AppLayout from "@/Layouts/AppLayout.js";
import ThemeToggle from "@/Components/ThemeToggle.js";

interface AdminUser {
    id: number;
    is_admin: boolean;
    main_character_id: boolean;
    main_character: MainCharacter
}

interface AdminCharacter {
    CharacterID: number;
    CharacterName: string;
    user: AdminUser | null;
    scopes: string[] | null;

}

interface MainCharacter {
    CharacterID: string;
    CharacterName: string;
}

interface PaginatedCharacters {
    data: AdminCharacter[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

type TokenTestResult = "success" | "failed" | "rate_limited";

async function getCharacters(
    search: string = "",
    page: number = 1,
): Promise<PaginatedCharacters> {
    const response = await axios.get("/admin/characters/list", {
        params: {
            search: search || undefined,
            page,
        },
    });

    return response.data;
}

async function testToken(characterId: number): Promise<TokenTestResult> {
    try {
        const response = await axios.get(
            `/admin/characters/${characterId}/test-token`,
        );

        return "success";
    } catch (error: any) {
        if (error.response?.status === 429) {
            return "rate_limited";
        }

        return "failed";
    }
}

export default function AdminCharacters() {
    const [characters, setCharacters] =
        useState<PaginatedCharacters | null>(null);

    const [search, setSearch] = useState(() => new URLSearchParams(window.location.search).get("search") ?? "");
    const [loading, setLoading] = useState(true);

    const [expandedCharacters, setExpandedCharacters] = useState<Set<number>>(
        new Set(),
    );

    const [testingCharacterId, setTestingCharacterId] = useState<number | null>(
        null,
    );

    const [tokenResults, setTokenResults] = useState<
        Map<number, TokenTestResult>
    >(new Map());

    useEffect(() => {
        const timeout = setTimeout(() => {
            loadCharacters(1);
        }, 250);

        return () => clearTimeout(timeout);
    }, [search]);

    const loadCharacters = async (page: number) => {
        setLoading(true);

        try {
            const data = await getCharacters(search, page);

            setCharacters(data);

            if (search.trim()) {
                setExpandedCharacters(
                    new Set(
                        data.data.map(
                            (character) => character.CharacterID,
                        ),
                    ),
                );
            } else {
                setExpandedCharacters(new Set());
            }
        } finally {
            setLoading(false);
        }
    };

    const toggleExpanded = (characterId: number) => {
        setExpandedCharacters((current) => {
            const next = new Set(current);

            if (next.has(characterId)) {
                next.delete(characterId);
            } else {
                next.add(characterId);
            }

            return next;
        });
    };

    const handleTokenTest = async (characterId: number) => {
        setTestingCharacterId(characterId);

        try {
            const result = await testToken(characterId);

            setTokenResults((current) => {
                const next = new Map(current);

                next.set(characterId, result);

                return next;
            });
        } finally {
            setTestingCharacterId(null);
        }
    };

    return (
        <AppLayout>
            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">
                        Character Administration
                    </h1>

                    <ThemeToggle />
                </div>

                <div className="border border-zinc-800 rounded-lg">
                    <div className="p-4 border-b border-zinc-800 space-y-3">
                        <div>
                            <h2 className="font-semibold">
                                Characters
                            </h2>

                            <p className="text-sm text-zinc-400 mt-1">
                                Manage characters, ownership and ESI
                                authentication.
                            </p>
                        </div>

                        <input
                            type="text"
                            value={search}
                            onChange={(event) => {
                                setSearch(event.target.value);
                            }}
                            placeholder="Search by character..."
                            className="
                                w-full
                                px-3 py-2
                                rounded
                                bg-zinc-950
                                border border-zinc-800
                                text-sm
                                placeholder:text-zinc-500
                                focus:outline-none
                                focus:border-zinc-600
                            "
                        />
                    </div>

                    <div>
                        {loading ? (
                            <div className="p-5 text-sm text-zinc-400">
                                Loading characters...
                            </div>
                        ) : characters?.data.length === 0 ? (
                            <div className="p-5 text-sm text-zinc-400">
                                No characters found.
                            </div>
                        ) : (
                            characters?.data.map((character) => (
                                <CharacterRow
                                    key={character.CharacterID}
                                    character={character}
                                    expanded={expandedCharacters.has(
                                        character.CharacterID,
                                    )}
                                    search={search}
                                    testing={
                                        testingCharacterId ===
                                        character.CharacterID
                                    }
                                    tokenResult={tokenResults.get(
                                        character.CharacterID,
                                    )}
                                    onToggleExpanded={toggleExpanded}
                                    onTestToken={handleTokenTest}
                                />
                            ))
                        )}
                    </div>

                    {characters && characters.last_page > 1 && (
                        <div className="flex items-center justify-between p-4 border-t border-zinc-800">
                            <span className="text-sm text-zinc-400">
                                Page {characters.current_page} of{" "}
                                {characters.last_page}
                            </span>

                            <div className="flex gap-2">
                                <button
                                    type="button"
                                    disabled={
                                        loading ||
                                        characters.current_page === 1
                                    }
                                    onClick={() =>
                                        loadCharacters(
                                            characters.current_page - 1,
                                        )
                                    }
                                    className="
                                        px-3 py-2
                                        rounded
                                        border border-zinc-700
                                        hover:bg-zinc-900
                                        disabled:opacity-40
                                        disabled:cursor-not-allowed
                                    "
                                >
                                    Previous
                                </button>

                                <button
                                    type="button"
                                    disabled={
                                        loading ||
                                        characters.current_page ===
                                        characters.last_page
                                    }
                                    onClick={() =>
                                        loadCharacters(
                                            characters.current_page + 1,
                                        )
                                    }
                                    className="
                                        px-3 py-2
                                        rounded
                                        border border-zinc-700
                                        hover:bg-zinc-900
                                        disabled:opacity-40
                                        disabled:cursor-not-allowed
                                    "
                                >
                                    Next
                                </button>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}

function CharacterRow({
    character,
    expanded,
    search,
    testing,
    tokenResult,
    onToggleExpanded,
    onTestToken,
}: {
    character: AdminCharacter;
    expanded: boolean;
    search: string;
    testing: boolean;
    tokenResult: string | undefined;
    onToggleExpanded: (characterId: number) => void;
    onTestToken: (characterId: number) => void;
}) {
    const normalizedSearch = search.trim().toLowerCase();

    const isMatch =
        normalizedSearch.length > 0 &&
        character.CharacterName.toLowerCase().includes(
            normalizedSearch,
        );

    return (
        <div className="border-b border-zinc-900 last:border-b-0">
            <div className="grid grid-cols-[1fr_auto] items-center px-4 py-3">
                <button
                    type="button"
                    onClick={() =>
                        onToggleExpanded(character.CharacterID)
                    }
                    className="
                        flex
                        items-center
                        gap-3
                        min-w-0
                        text-left
                        hover:text-zinc-300
                    "
                >
                    <span
                        className={`
                            text-xs
                            transition-transform
                            ${expanded ? "rotate-90" : ""}
                        `}
                    >
                        ▶
                    </span>

                    <div className="min-w-0">
                        <div
                            className={
                                isMatch
                                    ? "font-medium text-zinc-100"
                                    : "font-medium"
                            }
                        >
                            {character.CharacterName}
                        </div>

                        <div className="text-xs text-zinc-500">
                            Character #{character.CharacterID}
                            {" · "}
                            {character.user
                                ? `User #${character.user.id}`
                                : "No user"}
                            {" · "}
                            {character.user?.main_character_id
                                ? "Main"
                                : "Alt"}
                        </div>
                    </div>
                </button>

                <button
                    type="button"
                    disabled={testing}
                    onClick={() =>
                        onTestToken(character.CharacterID)
                    }
                    className="
                        px-3 py-2
                        rounded
                        border border-zinc-700
                        hover:bg-zinc-900
                        disabled:opacity-40
                        disabled:cursor-not-allowed
                    "
                >
                    {testing
                        ? "Testing..."
                        : tokenResult === undefined
                            ? "Test Token"
                            : tokenResult === "success"
                                ? "Token OK"
                                : tokenResult === "rate_limited"
                                    ? "Rate Limited"
                                    : "Token Failed"}
                </button>
            </div>

            {expanded && (
                <div className="px-10 pb-4 space-y-4">
                    <div className="space-y-1">
                        <div className="text-sm font-medium">
                            Character
                        </div>

                        <div className="text-sm text-zinc-400">
                            ID: {character.CharacterID}
                        </div>

                        <div className="text-sm text-zinc-400">
                            Main character:{" "}
                            {character.user?.main_character_id
                                ? "Yes"
                                : "No"}
                        </div>

                        {character.user && (
                            <Link
                                href={`/admin/users?search=${encodeURIComponent(
                                    character.user.main_character.CharacterName,
                                )}`}
                                className="
                                    inline-block
                                    text-sm
                                    text-zinc-300
                                    hover:text-zinc-100
                                    underline
                                "
                            >
                                View User #{character.user.id}
                            </Link>
                        )}
                    </div>

                    <div className="space-y-2">
                        <div className="text-sm font-medium">
                            ESI Scopes
                        </div>

                        {character.scopes?.length === 0 || character.scopes === null ? (
                            <div className="text-sm text-zinc-500">
                                No scopes available.
                            </div>
                        ) : (
                            <div className="space-y-1">
                                <div className="grid grid-cols-4 gap-1">
                                    {character.scopes.map((scope) => (
                                        <div
                                            key={scope}
                                            className="
                                            text-sm
                                            text-zinc-400
                                            px-3 py-1.5
                                            rounded
                                            bg-zinc-900
                                            border border-zinc-800
                                            truncate
                                        "
                                            title={scope}
                                        >
                                            {scope}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>

                    {tokenResult !== undefined && (
                        <div className="text-sm">
                            Token status:{" "}
                            <span
                                className={
                                    tokenResult
                                        ? "text-green-500"
                                        : "text-red-500"
                                }
                            >
                                {tokenResult
                                    ? "Working"
                                    : "Invalid / Failed"}
                            </span>
                        </div>
                    )}
                </div>
            )}
        </div>
    );
}
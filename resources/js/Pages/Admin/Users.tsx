import { useEffect, useState } from "react";
import { usePage, Link } from "@inertiajs/react";
import axios from "@/lib/axios.js";
import AppLayout from "@/Layouts/AppLayout.js";
import ThemeToggle from "@/Components/ThemeToggle.js";

interface Character {
    CharacterID: number;
    CharacterName: string;
}

interface AdminUser {
    id: number;
    is_admin: boolean;
    main_character: Character | null;
    characters: Character[];
}

interface PaginatedUsers {
    data: AdminUser[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

interface AuthUser {
    id: number;
}

interface PageProps {
    auth: {
        user: AuthUser;
    };
}

async function getUsers(
    search: string = "",
    page: number = 1,
): Promise<PaginatedUsers> {
    const response = await axios.get("/admin/users/list", {
        params: {
            search: search || undefined,
            page,
        },
    });

    return response.data;
}

async function setAdmin(
    userId: number,
    isAdmin: boolean,
): Promise<void> {
    await axios.patch(
        `/admin/users/${userId}/admin`,
        {
            is_admin: isAdmin,
        },
    );
}

export default function AdminUsers() {
    const { auth } = usePage<PageProps>().props;

    const [users, setUsers] = useState<PaginatedUsers | null>(null);
    const [search, setSearch] = useState(() => new URLSearchParams(window.location.search).get("search") ?? "");
    const [loading, setLoading] = useState(true);
    const [updatingUserId, setUpdatingUserId] = useState<number | null>(null);
    const [expandedUsers, setExpandedUsers] = useState<Set<number>>(
        new Set(),
    );

    useEffect(() => {
        const timeout = setTimeout(() => {
            loadUsers(1);
        }, 250);

        return () => clearTimeout(timeout);
    }, [search]);

    const loadUsers = async (page: number) => {
        setLoading(true);

        try {
            const data = await getUsers(search, page);

            setUsers(data);

            if (search.trim()) {
                setExpandedUsers(
                    new Set(data.data.map((user) => user.id)),
                );
            } else {
                setExpandedUsers(new Set());
            }
        } finally {
            setLoading(false);
        }
    };

    const toggleExpanded = (userId: number) => {
        setExpandedUsers((current) => {
            const next = new Set(current);

            if (next.has(userId)) {
                next.delete(userId);
            } else {
                next.add(userId);
            }

            return next;
        });
    };

    const toggleAdmin = async (user: AdminUser) => {
        setUpdatingUserId(user.id);

        try {
            await setAdmin(user.id, !user.is_admin);

            setUsers((current) => {
                if (!current) {
                    return current;
                }

                return {
                    ...current,
                    data: current.data.map((item) =>
                        item.id === user.id
                            ? {
                                ...item,
                                is_admin: !item.is_admin,
                            }
                            : item,
                    ),
                };
            });
        } finally {
            setUpdatingUserId(null);
        }
    };

    return (
        <AppLayout>
            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">
                        User Administration
                    </h1>

                    <ThemeToggle />
                </div>

                <div className="border border-zinc-800 rounded-lg">
                    <div className="p-4 border-b border-zinc-800 space-y-3">
                        <div>
                            <h2 className="font-semibold">
                                Users
                            </h2>

                            <p className="text-sm text-zinc-400 mt-1">
                                Manage administrator roles and characters.
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
                                Loading users...
                            </div>
                        ) : users?.data.length === 0 ? (
                            <div className="p-5 text-sm text-zinc-400">
                                No users found.
                            </div>
                        ) : (
                            users?.data.map((user) => (
                                <UserRow
                                    key={user.id}
                                    user={user}
                                    isCurrentUser={
                                        user.id === auth.user.id
                                    }
                                    expanded={expandedUsers.has(user.id)}
                                    search={search}
                                    updating={
                                        updatingUserId === user.id
                                    }
                                    onToggleExpanded={toggleExpanded}
                                    onToggleAdmin={toggleAdmin}
                                />
                            ))
                        )}
                    </div>

                    {users && users.last_page > 1 && (
                        <div className="flex items-center justify-between p-4 border-t border-zinc-800">
                            <span className="text-sm text-zinc-400">
                                Page {users.current_page} of{" "}
                                {users.last_page}
                            </span>

                            <div className="flex gap-2">
                                <button
                                    type="button"
                                    disabled={
                                        loading ||
                                        users.current_page === 1
                                    }
                                    onClick={() =>
                                        loadUsers(
                                            users.current_page - 1,
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
                                        users.current_page ===
                                        users.last_page
                                    }
                                    onClick={() =>
                                        loadUsers(
                                            users.current_page + 1,
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

function UserRow({
    user,
    isCurrentUser,
    expanded,
    search,
    updating,
    onToggleExpanded,
    onToggleAdmin,
}: {
    user: AdminUser;
    isCurrentUser: boolean;
    expanded: boolean;
    search: string;
    updating: boolean;
    onToggleExpanded: (userId: number) => void;
    onToggleAdmin: (user: AdminUser) => void;
}) {
    const cannotRemoveAdmin =
        isCurrentUser && user.is_admin;

    return (
        <div className="border-b border-zinc-900 last:border-b-0">
            <div className="grid grid-cols-[1fr_auto] items-center px-4 py-3">
                <button
                    type="button"
                    onClick={() => onToggleExpanded(user.id)}
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
                        <div className="font-medium">
                            {user.main_character?.CharacterName ??
                                "No main character"}
                        </div>

                        <div className="text-xs text-zinc-500">
                            User #{user.id}
                            {isCurrentUser && " · You"}
                            {" · "}
                            {user.characters.length}{" "}
                            {user.characters.length === 1
                                ? "character"
                                : "characters"}
                        </div>
                    </div>
                </button>

                <button
                    type="button"
                    disabled={
                        updating || cannotRemoveAdmin
                    }
                    onClick={() => onToggleAdmin(user)}
                    className="
                        px-3 py-2
                        rounded
                        border border-zinc-700
                        hover:bg-zinc-900
                        disabled:opacity-40
                        disabled:cursor-not-allowed
                    "
                >
                    {updating
                        ? "Updating..."
                        : user.is_admin
                            ? "Remove Admin"
                            : "Make Admin"}
                </button>
            </div>

            {expanded && (
                <div className="px-10 pb-3 space-y-1">
                    {user.characters.map((character) => (
                        <CharacterRow
                            key={character.CharacterID}
                            character={character}
                            search={search}
                        />
                    ))}
                </div>
            )}
        </div>
    );
}

function CharacterRow({
    character,
    search,
}: {
    character: Character;
    search: string;
}) {
    const normalizedSearch = search.trim().toLowerCase();

    const isMatch =
        normalizedSearch.length > 0 &&
        character.CharacterName
            .toLowerCase()
            .includes(normalizedSearch);

    return (
        <div
            className={`
            text-sm
            px-3 py-1.5
            rounded
            ${isMatch
                    ? "text-zinc-100 bg-zinc-800"
                    : "text-zinc-400"
                }
        `}
        >
            <Link
                href={`/admin/characters?search=${encodeURIComponent(
                    character.CharacterName,
                )}`}
                className="hover:text-zinc-100 hover:underline"
            >
                {character.CharacterName}
            </Link>
        </div>
    );
}
import { Link, usePage } from "@inertiajs/react";
import {
    ChevronDown,
    ChevronRight,
    LogOut,
    Shield,
} from "lucide-react";
import { useState } from "react";

type Character = {
    CharacterID: number
    CharacterName: string
}

export default function AppLayout({ children }: { children: React.ReactNode }) {
    const { url } = usePage();

    const { auth } = usePage().props;

    const isAdmin = auth?.user.is_admin;

    const mainCharacter = auth?.user?.characters?.find(
        (character: Character) => character.CharacterID === auth?.user?.main_character_id
    );

    const isAdminRoute = url.startsWith("/admin");

    const [adminOpen, setAdminOpen] = useState(isAdminRoute);

    const navItems = [
        { name: "Dashboard", href: "/dashboard" },
        { name: "Industry planner", href: "/industry/plans" },
    ];

    const adminNavItems = [
        { name: "Market Settings", href: "/admin/market" },
        { name: "Users", href: "/admin/users" },
        { name: "Characters", href: "/admin/characters" },
        { name: "Scopes", href: "/admin/scopes" },
    ];

    return (
        <div className="flex min-h-screen bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100">

            {/* Sidebar */}
            <aside className="w-64 border-r border-zinc-200 dark:border-zinc-800 p-4 flex flex-col">
                <div className="flex-1">
                    <h1 className="text-lg font-bold mb-6">
                        Capsuleer Panel
                    </h1>

                    <nav className="space-y-2">
                        {navItems.map((item) => {
                            const active = url === item.href;

                            return (
                                <Link
                                    key={item.href}
                                    href={item.href}
                                    className={`block px-3 py-2 rounded-lg text-sm transition
                                        ${active
                                            ? "bg-zinc-200 dark:bg-zinc-800 font-semibold"
                                            : "hover:bg-zinc-100 dark:hover:bg-zinc-900"
                                        }`}
                                >
                                    {item.name}
                                </Link>
                            );
                        })}

                        {/* Admin group */}
                        {isAdmin && (
                            <div>
                                <button
                                    type="button"
                                    onClick={() => setAdminOpen((open: boolean) => !open)}
                                    className={`w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition
                                    ${isAdminRoute
                                            ? "bg-zinc-200 dark:bg-zinc-800 font-semibold"
                                            : "hover:bg-zinc-100 dark:hover:bg-zinc-900"
                                        }`}
                                >
                                    <Shield size={16} />

                                    <span className="flex-1 text-left">
                                        Admin Panel
                                    </span>

                                    {adminOpen ? (
                                        <ChevronDown size={16} />
                                    ) : (
                                        <ChevronRight size={16} />
                                    )}
                                </button>

                                {adminOpen && (
                                    <div className="mt-1 ml-5 pl-3 border-l border-zinc-200 dark:border-zinc-800 space-y-1">
                                        {adminNavItems.map((item) => {
                                            const active = url === item.href;

                                            return (
                                                <Link
                                                    key={item.href}
                                                    href={item.href}
                                                    className={`block px-3 py-2 rounded-lg text-sm transition
                                                    ${active
                                                            ? "bg-zinc-200 dark:bg-zinc-800 font-semibold"
                                                            : "text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900 hover:text-zinc-900 dark:hover:text-zinc-100"
                                                        }`}
                                                >
                                                    {item.name}
                                                </Link>
                                            );
                                        })}
                                    </div>
                                )}
                            </div>
                        )}
                    </nav>
                </div>

                {/* User / Logout Section */}
                <div className="pt-4 mt-4 border-t border-zinc-200 dark:border-zinc-800">
                    {auth?.user.main_character_id && (
                        <div className="flex items-center gap-3 px-3 mb-3">
                            <img
                                src={`https://images.evetech.net/characters/${mainCharacter.CharacterID}/portrait?size=32`}
                                alt={auth.user.characters}
                                className="w-8 h-8 rounded-full"
                            />

                            <div className="min-w-0">
                                <div className="text-sm font-medium truncate">
                                    {mainCharacter.CharacterName}
                                </div>

                                <div className="text-xs text-zinc-500 dark:text-zinc-400">
                                    Main character
                                </div>
                            </div>
                        </div>
                    )}

                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        className="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition font-medium"
                    >
                        <LogOut size={16} />
                        Logout
                    </Link>
                </div>
            </aside>

            {/* Main content */}
            <main className="flex-1 p-6">
                {children}
            </main>
        </div>
    );
}
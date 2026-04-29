import { Link, usePage } from "@inertiajs/react";
import { LogOut } from "lucide-react"; // Optional: if you're using lucide icons

export default function AppLayout({ children }: { children: React.ReactNode }) {
    const { url } = usePage();

    const navItems = [
        { name: "Dashboard", href: "/dashboard" },
        { name: "Industry planner", href: "/industry" },
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
                    </nav>
                </div>

                {/* Logout Section */}
                <div className="pt-4 mt-4 border-t border-zinc-200 dark:border-zinc-800">
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
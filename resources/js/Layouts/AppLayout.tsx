import { Link, usePage } from "@inertiajs/react";

export default function AppLayout({ children }: { children: React.ReactNode }) {
    const { url } = usePage();

    const navItems = [
        { name: "Dashboard", href: "/dashboard" },
        { name: "Industry planner", href: "/industry" },
    ];

    return (
        <div className="flex min-h-screen bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100">

            {/* Sidebar */}
            <aside className="w-64 border-r border-zinc-200 dark:border-zinc-800 p-4">
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
            </aside>

            {/* Main content */}
            <main className="flex-1 p-6">
                {children}
            </main>
        </div>
    );
}
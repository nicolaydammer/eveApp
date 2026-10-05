import { useTheme } from "next-themes";

export default function ThemeToggle() {
    const { theme, setTheme } = useTheme();

    return (
        <button
            type="button"
            onClick={() => setTheme(theme === "dark" ? "light" : "dark")}
            className="rounded-md border border-zinc-300 px-4 py-2 text-sm
                hover:bg-zinc-100 hover:border-zinc-400
                dark:border-zinc-700 dark:hover:bg-zinc-800
                dark:hover:border-zinc-600
                transition-colors"
        >
            Toggle Theme
        </button>
    );
}
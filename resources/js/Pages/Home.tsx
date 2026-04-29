import { router } from '@inertiajs/react';
import { route } from 'ziggy-js';

export default function Home() {
    return (
        <div className="flex flex-col items-center justify-center min-h-screen bg-black">
            <button
                onClick={() => window.location.href = route('auth.redirectToEveSSO')}
                className="group relative inline-flex items-center justify-center px-8 py-3 font-bold text-black transition-all duration-200 bg-[#F6B900] hover:bg-[#ffcc33] active:scale-95 uppercase tracking-widest border-2 border-[#F6B900]"
                style={{ clipPath: 'polygon(10% 0, 100% 0, 100% 70%, 90% 100%, 0 100%, 0 30%)' }}
            >
                {/* Visual Flair: The "E" Logo lookalike or simple text */}
                <span className="mr-3 text-xl font-black">//</span>
                Login with EVE Online

                {/* Subtle border glow effect */}
                <div className="absolute inset-0 border border-white/20 pointer-events-none"></div>
            </button>

            <p className="mt-4 text-xs text-gray-500 uppercase tracking-tighter">
                Secure Authentication via CCP Games
            </p>
        </div>
    );
}

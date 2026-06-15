import React, { useState, useMemo } from 'react';

export default function BlueprintShoppingListModal({ isOpen, onClose, blueprints }) {
    const [copied, setCopied] = useState(false);
    const [searchTerm, setSearchTerm] = useState('');
    const [activeTab, setActiveTab] = useState('all'); // options: 'all', 'manufacturing', 'reaction'

    // HOOK: Filter and search calculations must always run first to satisfy React Hook rules
    const filteredBlueprints = useMemo(() => {
        return blueprints.filter(bp => {
            const matchesSearch = bp.name.toLowerCase().includes(searchTerm.toLowerCase());
            const matchesTab = activeTab === 'all' || bp.activityType === activeTab;
            return matchesSearch && matchesTab;
        });
    }, [blueprints, searchTerm, activeTab]);

    // SAFETY CHECK: Conditional return placed safely below all hook initializations
    if (!isOpen) return null;

    const handleCopyBlueprints = () => {
        if (filteredBlueprints.length === 0) return;
        // Copies filtered scope directly to clipboards as a game-client compatible Multibuy block
        const outputText = filteredBlueprints.map(bp => `${bp.name}\t${bp.copiesNeeded}`).join('\n');

        navigator.clipboard.writeText(outputText).then(() => {
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        });
    };

    const manufacturingCount = blueprints.filter(b => b.activityType === 'manufacturing').length;
    const reactionCount = blueprints.filter(b => b.activityType === 'reaction').length;

    return (
        <div className="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div className="bg-slate-900 border border-slate-800 rounded-2xl max-w-3xl w-full overflow-hidden shadow-2xl flex flex-col max-h-[85vh]">

                {/* Header Block */}
                <div className="bg-slate-950 px-6 py-4 border-b border-slate-800 flex justify-between items-center shrink-0">
                    <div>
                        <h3 className="font-bold text-slate-200 uppercase tracking-wider text-sm">Industrial Blueprint Explorer</h3>
                        <p className="text-[10px] text-slate-500 uppercase font-semibold tracking-wider mt-0.5">Job slot run divisions and copy allocations</p>
                    </div>
                    <button onClick={onClose} className="text-slate-500 hover:text-slate-200 font-bold text-sm p-1">✕</button>
                </div>

                {/* Filters Row */}
                <div className="bg-slate-950/50 border-b border-slate-800/60 px-6 py-3 flex flex-col sm:flex-row gap-3 justify-between items-center shrink-0">
                    {/* Tabs Segment */}
                    <div className="flex bg-slate-950 p-1 rounded-xl border border-slate-800 w-full sm:w-auto">
                        <button
                            type="button"
                            onClick={() => setActiveTab('all')}
                            className={`flex-1 sm:flex-initial px-3 py-1.5 text-[11px] font-bold uppercase rounded-lg tracking-wider transition-all ${activeTab === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200'}`}
                        >
                            All ({blueprints.length})
                        </button>
                        <button
                            type="button"
                            onClick={() => setActiveTab('manufacturing')}
                            className={`flex-1 sm:flex-initial px-3 py-1.5 text-[11px] font-bold uppercase rounded-lg tracking-wider transition-all ${activeTab === 'manufacturing' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200'}`}
                        >
                            Manufacturing ({manufacturingCount})
                        </button>
                        <button
                            type="button"
                            onClick={() => setActiveTab('reaction')}
                            className={`flex-1 sm:flex-initial px-3 py-1.5 text-[11px] font-bold uppercase rounded-lg tracking-wider transition-all ${activeTab === 'reaction' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200'}`}
                        >
                            Reactions ({reactionCount})
                        </button>
                    </div>

                    {/* Live Search Input */}
                    <div className="relative w-full sm:w-64">
                        <input
                            type="text"
                            placeholder="Filter item blueprints..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl px-3 py-1.5 text-xs text-slate-200 placeholder-slate-500 focus:outline-none"
                        />
                        {searchTerm && (
                            <button type="button" onClick={() => setSearchTerm('')} className="absolute right-3 top-2 text-slate-500 hover:text-slate-300 text-xs">✕</button>
                        )}
                    </div>
                </div>

                {/* Scrollable Blueprint Content Body */}
                <div className="p-6 overflow-y-auto space-y-3 flex-1 bg-slate-950/20">
                    {filteredBlueprints.length === 0 ? (
                        <div className="p-12 text-center text-slate-500 text-xs uppercase tracking-wider font-semibold">No active blueprints match current filter scope.</div>
                    ) : (
                        <div className="border border-slate-800/60 rounded-xl overflow-hidden bg-slate-950/50 shadow-inner">
                            <table className="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr className="bg-slate-900 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider select-none">
                                        <th className="px-5 py-3 w-[45%]">Blueprint Name</th>
                                        <th className="px-4 py-3 text-center w-[15%]">Type</th>
                                        <th className="px-4 py-3 text-right w-[13%]">Total Runs</th>
                                        <th className="px-4 py-3 text-right w-[13%]">Runs/BPC</th>
                                        <th className="px-5 py-3 text-right w-[14%] text-indigo-400 font-extrabold">Copies Needed</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-900 font-mono">
                                    {filteredBlueprints.map((bp, index) => (
                                        <tr key={index} className="hover:bg-slate-900/30 text-slate-300 group">
                                            <td className="px-5 py-3 text-left font-sans font-medium text-slate-200 truncate max-w-[240px]">{bp.name}</td>
                                            <td className="px-4 py-3 text-center font-sans">
                                                <span className={`px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider ${bp.activityType === 'reaction' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20'}`}>
                                                    {bp.activityType === 'reaction' ? 'React' : 'Manuf'}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3 text-right text-slate-400">{bp.totalRuns.toLocaleString()}</td>
                                            <td className="px-4 py-3 text-right text-slate-300 font-semibold">{bp.runsPerCopy.toLocaleString()}</td>
                                            <td className="px-5 py-3 text-right text-indigo-400 font-black text-sm bg-indigo-950/5 group-hover:bg-indigo-950/20 transition-colors">{bp.copiesNeeded.toLocaleString()}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>

                {/* Footer Control Actions */}
                <div className="bg-slate-950 px-6 py-4 border-t border-slate-800 flex justify-between items-center shrink-0">
                    <button
                        type="button"
                        onClick={handleCopyBlueprints}
                        disabled={filteredBlueprints.length === 0}
                        className={`px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider border transition-all shadow-md ${copied
                                ? 'bg-emerald-950/40 border-emerald-500 text-emerald-400 font-extrabold'
                                : 'bg-slate-900 border-slate-800 hover:bg-slate-800 text-indigo-400'
                            }`}
                    >
                        {copied ? '✓ Copied Filtered Selection' : `📋 Copy Visible for Multibuy (${filteredBlueprints.length})`}
                    </button>
                    <button type="button" onClick={onClose} className="bg-slate-800 hover:bg-slate-700 text-slate-300 px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md">Close</button>
                </div>
            </div>
        </div>
    );
}
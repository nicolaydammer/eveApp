import React, { useState } from 'react';
import DirectBuyTable from '@/Components/DirectBuyTable.js';
import FullTreeTable from '@/Components/FullTreeTable.js';
import { formatNum, formatISK, formatDuration } from '@/Components/helpers/industryUtils.js';

export default function MaterialsTableDisplay({
    buildMode, loading, materials, groupedMaterials, deconStates, onToggleDecon,
    collapsedNodes, onToggleCollapse, batchMultiplier, materialModifier,
    baseProductionTime, totalDurationSeconds, payloadName, grandTotalVolume, grandTotalPrice,
    onCopyClipboard
}) {
    const [bottomCopied, setBottomCopied] = useState(false);

    const handleBottomCopy = () => {
        onCopyClipboard();
        setBottomCopied(true);
        setTimeout(() => setBottomCopied(false), 2000);
    };

    return (
        <div className="space-y-4">
            <div className="border border-slate-800/80 rounded-2xl bg-slate-950/40 shadow-2xl overflow-hidden relative min-h-[160px] backdrop-blur-md">
                {loading && (
                    <div className="absolute inset-0 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center z-10">
                        <svg className="animate-spin h-9 w-9 text-indigo-500" fill="none" viewBox="0 0 24 24">
                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                    </div>
                )}

                <div className="bg-slate-900/60 px-5 py-3.5 border-b border-slate-800/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <h3 className="text-xs font-bold text-slate-400 uppercase tracking-widest">
                        {buildMode === 'tree' ? 'Resource Hierarchy Breakdown' : 'Required Materials'}
                    </h3>
                    <div className="flex flex-wrap items-center gap-2">
                        {baseProductionTime > 0 && (
                            <span className="text-xs bg-slate-950 px-3 py-1 rounded-lg font-mono text-amber-400 font-bold border border-slate-800 shadow-sm">
                                Total Time: {formatDuration(totalDurationSeconds)}
                            </span>
                        )}
                        {payloadName && (
                            <span className="text-xs bg-slate-900 px-3 py-1 rounded-lg font-mono text-indigo-400 font-bold border border-slate-800 shadow-sm">
                                {payloadName} {batchMultiplier > 1 && `(x${batchMultiplier})`}
                            </span>
                        )}
                    </div>
                </div>

                {!loading && materials.length === 0 ? (
                    <div className="p-12 text-center text-slate-500 text-sm font-medium tracking-wide">No data found for this layout configuration.</div>
                ) : buildMode === 'tree' ? (
                    <FullTreeTable
                        materials={materials} deconStates={deconStates} onToggleDecon={onToggleDecon}
                        collapsedNodes={collapsedNodes} onToggleCollapse={onToggleCollapse}
                        batchMultiplier={batchMultiplier} materialModifier={materialModifier}
                    />
                ) : (
                    <DirectBuyTable
                        groupedMaterials={groupedMaterials} batchMultiplier={batchMultiplier} materialModifier={materialModifier}
                        grandTotalVolume={grandTotalVolume} grandTotalPrice={grandTotalPrice}
                    />
                )}
            </div>

            {/* Separated Multibuy Button Render Wrapper outside table boundaries */}
            {buildMode === 'direct' && materials.length > 0 && (
                <div className="flex justify-end pt-2">
                    <button
                        type="button"
                        onClick={handleBottomCopy}
                        className={`inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold rounded-xl border uppercase tracking-wider transition-all duration-150 shadow-lg ${bottomCopied ? 'bg-emerald-950/40 border-emerald-500 text-emerald-400' : 'bg-slate-900 border-slate-800 hover:bg-slate-800 text-indigo-400'}`}
                    >
                        {bottomCopied ? '✓ Copied Multibuy' : '📋 Copy Multibuy'}
                    </button>
                </div>
            )}

            {buildMode === 'tree' && (
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div className="bg-slate-900/40 border border-slate-800/80 p-6 rounded-2xl flex justify-between items-center relative overflow-hidden shadow-xl backdrop-blur-sm">
                        <div>
                            <p className="text-[10px] font-black uppercase text-slate-500 tracking-widest mb-1">Logistics Volume Needed</p>
                            <h2 className="text-3xl font-black font-mono text-indigo-400">{formatNum(grandTotalVolume)} <span className="text-sm font-bold text-slate-500 uppercase">m³</span></h2>
                        </div>
                        <span className="text-3xl opacity-20 text-slate-400 select-none">🚚</span>
                    </div>

                    <div className="bg-slate-900/40 border border-slate-800/80 p-6 rounded-2xl flex justify-between items-center relative overflow-hidden shadow-xl backdrop-blur-sm">
                        <div>
                            <p className="text-[10px] font-black uppercase text-slate-500 tracking-widest mb-1">Project ISK Capital Required</p>
                            <h2 className="text-3xl font-black font-mono text-emerald-400">{formatISK(grandTotalPrice)} <span className="text-sm font-bold text-slate-500 uppercase">ISK</span></h2>
                        </div>
                        <span className="text-3xl opacity-20 text-slate-400 select-none">⚡</span>
                    </div>
                </div>
            )}
        </div>
    );
}
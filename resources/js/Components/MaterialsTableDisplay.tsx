import React, { useState, useRef, useEffect } from 'react';

export default function MaterialsTableDisplay({
    buildMode,
    loading,
    materials,
    deconStates,
    onToggleDecon,
    collapsedNodes,
    onToggleCollapse,
    batchMultiplier,
    materialModifier,
    structureMeModifier,
    baseProductionTime,
    totalDurationSeconds,
    payloadName,
    grandTotalVolume,
    grandTotalPrice,
    onCopyClipboard,
    outputProduct,
    onCopySubTree,
    searchTerm,
    setSearchTerm,
    onBulkDeconstruct,
    onBulkCollapse
}) {
    const [activeMenuId, setActiveMenuId] = useState<string | null>(null);
    const [justCopiedId, setJustCopiedId] = useState<string | null>(null);
    const menuRef = useRef<HTMLDivElement | null>(null);

    useEffect(() => {
        function handleClickOutside(event: MouseEvent) {
            if (menuRef.current && !menuRef.current.contains(event.target as Node)) {
                setActiveMenuId(null);
            }
        }
        document.addEventListener("mousedown", handleClickOutside);
        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    const formatISK = (value: number) => {
        return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
    };

    const formatM3 = (value: number) => {
        return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
    };

    const formatTime = (seconds: number) => {
        if (!seconds || seconds <= 0) return '0s';
        const d = Math.floor(seconds / (3600 * 24));
        const h = Math.floor((seconds % (3600 * 24)) / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);
        return `${d > 0 ? d + 'd ' : ''}${h > 0 ? h + 'h ' : ''}${m > 0 ? m + 'm ' : ''}${s > 0 ? s + 's' : ''}`.trim();
    };

    const getNodeVolume = (node: any, currentMultiplier: number): number => {
        const isDeconstructed = !!deconStates[node.path];
        const hasChildren = node.children && node.children.length > 0;
        const displayQty = Math.ceil(node.quantity * currentMultiplier * structureMeModifier);
        const baseItemVolume = (node.packaged_volume || node.unit_volume || 0) * displayQty;

        if (buildMode === 'tree' && hasChildren && isDeconstructed) {
            const childrenVolume = node.children.reduce((acc: number, child: any) => {
                return acc + getNodeVolume(child, displayQty / node.quantity);
            }, 0);
            return childrenVolume + baseItemVolume;
        } else {
            return baseItemVolume;
        }
    };

    const copyImmediateInputs = (node: any, currentMultiplier: number) => {
        if (!node.children || node.children.length === 0) return;
        const parentQty = Math.ceil(node.quantity * currentMultiplier * structureMeModifier);
        const lines = node.children.map((child: any) => {
            const childQty = Math.ceil(child.quantity * (parentQty / node.quantity) * structureMeModifier);
            return `${child.name} ${childQty}`;
        });
        navigator.clipboard.writeText(lines.join('\n'));
        triggerCopyFeedback(node.path);
    };

    const copyRecursiveSubTree = (node: any, currentMultiplier: number) => {
        const parseLines = (item: any, multiplier: number) => {
            let lines = [];
            const isDeconstructed = !!deconStates[item.path];
            const displayQty = Math.ceil(item.quantity * multiplier * structureMeModifier);

            if (item.children && item.children.length > 0 && isDeconstructed) {
                item.children.forEach((child: any) => {
                    lines = [...lines, ...parseLines(child, displayQty / item.quantity)];
                });
            } else {
                lines.push(`${item.name} ${displayQty}`);
            }
            return lines;
        };

        let outputLines = [];
        const parentQty = Math.ceil(node.quantity * currentMultiplier * structureMeModifier);
        if (node.children && node.children.length > 0 && deconStates[node.path]) {
            node.children.forEach((child: any) => {
                outputLines = [...outputLines, ...parseLines(child, parentQty / node.quantity)];
            });
        } else {
            outputLines.push(`${node.name} ${parentQty}`);
        }
        navigator.clipboard.writeText(outputLines.join('\n'));
        triggerCopyFeedback(node.path);
    };

    const triggerCopyFeedback = (nodePath: string) => {
        setJustCopiedId(nodePath);
        setActiveMenuId(null);
        setTimeout(() => setJustCopiedId(null), 1500);
    };

    const highlightText = (text: string, highlight: string) => {
        if (!highlight.trim()) return <span className="text-slate-300 font-semibold">{text}</span>;
        const parts = text.split(new RegExp(`(${highlight.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&')})`, 'gi'));
        return (
            <span className="text-slate-300 font-semibold">
                {parts.map((part, i) =>
                    part.toLowerCase() === highlight.toLowerCase()
                        ? <mark key={i} className="bg-amber-500/30 text-amber-300 rounded px-0.5 font-bold border border-amber-500/20">{part}</mark>
                        : part
                )}
            </span>
        );
    };

    const renderRow = (node: any, depth = 0, parentQtyMultiplier = 1) => {
        const hasChildren = node.children && node.children.length > 0;
        const isDeconstructed = !!deconStates[node.path];
        const isCollapsed = !!collapsedNodes[node.path];

        const currentMultiplier = depth === 0 ? batchMultiplier * (1 - materialModifier) : parentQtyMultiplier;
        const displayQty = Math.ceil(node.quantity * currentMultiplier * structureMeModifier);
        const rowTotalVolume = depth === 0 ? getNodeVolume(node, batchMultiplier * (1 - materialModifier)) : getNodeVolume(node, parentQtyMultiplier);
        const totalRowPrice = (node.estimated_price || 0) * displayQty;

        const matchesSearch = node.name.toLowerCase().includes(searchTerm.toLowerCase());

        const checkChildMatch = (item: any): boolean => {
            if (item.name.toLowerCase().includes(searchTerm.toLowerCase())) return true;
            if (item.children && item.children.length > 0) {
                return item.children.some((c: any) => checkChildMatch(c));
            }
            return false;
        };
        const hasMatchingDescendant = hasChildren && node.children.some((c: any) => checkChildMatch(c));

        // If a search term exists and neither this row nor any child match, skip render entirely
        if (searchTerm && !matchesSearch && !hasMatchingDescendant) return null;

        // FIXED: Force visibility when searching so children elements are never hidden behind closed parents
        const isEffectiveCollapsed = searchTerm ? false : isCollapsed;
        const isEffectiveDeconstructed = searchTerm ? true : isDeconstructed;

        return (
            <React.Fragment key={node.path}>
                <tr className={`border-b border-slate-800/60 transition-colors hover:bg-slate-900/40 text-xs ${depth > 0 ? 'bg-slate-950/20' : ''} ${matchesSearch && searchTerm ? 'bg-indigo-950/20 shadow-inner' : ''}`}>
                    <td className="py-2 px-4 font-medium flex items-center gap-1" style={{ paddingLeft: `${Math.max(16, depth * 24)}px` }}>
                        {buildMode === 'tree' && hasChildren && (
                            <button type="button" onClick={() => onToggleCollapse(node.path)} className="w-4 h-4 flex items-center justify-center text-slate-500 hover:text-slate-300 font-mono select-none">
                                {isEffectiveCollapsed ? '▶' : '▼'}
                            </button>
                        )}
                        {buildMode === 'tree' && !hasChildren && <span className="w-4" />}
                        {highlightText(node.name, searchTerm)}
                    </td>

                    <td className="py-2 px-4 text-center font-mono font-bold text-indigo-400">
                        {displayQty.toLocaleString()}
                    </td>

                    <td className="py-2 px-4 text-right font-mono text-slate-400">
                        {formatM3(rowTotalVolume)} m³
                    </td>

                    <td className="py-2 px-4 text-right font-mono text-emerald-400">
                        {formatISK(totalRowPrice)} ISK
                    </td>

                    {buildMode === 'tree' && (
                        <td className="py-2 px-4 text-center overflow-visible relative">
                            <div className="flex items-center justify-center gap-2">
                                {hasChildren ? (
                                    <div className="relative inline-block text-left" ref={activeMenuId === node.path ? menuRef : null}>
                                        <button
                                            type="button"
                                            onClick={(e) => {
                                                e.stopPropagation();
                                                setActiveMenuId(activeMenuId === node.path ? null : node.path);
                                            }}
                                            className={`px-2 py-1 rounded-md text-[11px] font-bold transition-all flex items-center gap-1.5 ${justCopiedId === node.path
                                                    ? 'bg-emerald-950 text-emerald-400 border border-emerald-500/30'
                                                    : 'bg-slate-900/60 border border-slate-800 hover:border-indigo-500 text-slate-400 hover:text-indigo-400'
                                                }`}
                                        >
                                            {justCopiedId === node.path ? '✓ Copied' : '📋 Multibuy ▾'}
                                        </button>

                                        {activeMenuId === node.path && (
                                            <div className="absolute right-0 mt-1 w-48 rounded-xl bg-slate-950 border border-slate-800 shadow-2xl z-50 py-1.5">
                                                <button
                                                    type="button"
                                                    onClick={() => copyImmediateInputs(node, currentMultiplier)}
                                                    className="w-full text-left px-3 py-2 text-xs text-slate-300 hover:bg-slate-900/80 hover:text-indigo-400 font-medium transition-colors"
                                                >
                                                    Copy Immediate Inputs
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => copyRecursiveSubTree(node, currentMultiplier)}
                                                    className="w-full text-left px-3 py-2 text-xs text-slate-400 hover:bg-slate-900/80 hover:text-indigo-400 font-medium transition-colors border-t border-slate-900"
                                                >
                                                    Copy Full Sub-Tree
                                                </button>
                                            </div>
                                        )}
                                    </div>
                                ) : (
                                    <button
                                        type="button"
                                        onClick={() => {
                                            navigator.clipboard.writeText(`${node.name} ${displayQty}`);
                                            triggerCopyFeedback(node.path);
                                        }}
                                        className={`px-2 py-1 rounded-md text-[11px] font-bold transition-all ${justCopiedId === node.path
                                                ? 'bg-emerald-950 text-emerald-400 border border-emerald-500/30'
                                                : 'bg-slate-900/60 border border-slate-800 hover:border-indigo-500 text-slate-400 hover:text-indigo-400'
                                            }`}
                                    >
                                        {justCopiedId === node.path ? '✓ Copied' : '📋 Multibuy'}
                                    </button>
                                )}

                                {hasChildren && (
                                    <label className="inline-flex items-center cursor-pointer select-none">
                                        <input type="checkbox" checked={isEffectiveDeconstructed} onChange={() => onToggleDecon(node.path)} className="sr-only peer" />
                                        <div className="w-7 h-4 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-indigo-600 relative after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-slate-400 after:rounded-full after:h-3 after:w-3 after:transition-all"></div>
                                    </label>
                                )}
                            </div>
                        </td>
                    )}
                </tr>
                {buildMode === 'tree' && hasChildren && !isEffectiveCollapsed && isEffectiveDeconstructed && (
                    node.children.map((child: any) => renderRow(child, depth + 1, displayQty / node.quantity))
                )}
            </React.Fragment>
        );
    };

    if (loading) {
        return (
            <div className="w-full h-48 bg-slate-900/50 rounded-2xl border border-slate-800/80 flex items-center justify-center backdrop-blur-sm animate-pulse">
                <span className="text-xs uppercase tracking-widest font-black text-indigo-400/70">Analyzing Blueprints...</span>
            </div>
        );
    }

    if (!materials || materials.length === 0) {
        return (
            <div className="w-full h-48 bg-slate-900/50 rounded-2xl border border-slate-800/80 flex items-center justify-center backdrop-blur-sm">
                <span className="text-xs uppercase tracking-widest font-black text-slate-500">No Industrial Specifications Loaded</span>
            </div>
        );
    }

    return (
        <div className="bg-slate-900/50 rounded-2xl border border-slate-800 shadow-2xl backdrop-blur-md overflow-hidden w-full">
            {/* Stats Dashboard */}
            <div className="p-4 border-b border-slate-800 bg-slate-950/40 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <div className="text-[10px] font-bold uppercase tracking-widest text-slate-500">Item Focus</div>
                    <div className="text-sm font-bold text-slate-200 truncate">{payloadName || 'Unknown Blueprint'}</div>
                </div>
                <div>
                    <div className="text-[10px] font-bold uppercase tracking-widest text-slate-500">Production Velocity Duration</div>
                    <div className="text-sm font-mono font-bold text-amber-400">{formatTime(totalDurationSeconds)}</div>
                </div>
                <div>
                    <div className="text-[10px] font-bold uppercase tracking-widest text-slate-500">Logistics Volume Needed</div>
                    <div className="text-sm font-mono font-bold text-indigo-400">{formatM3(grandTotalVolume)} m³</div>
                </div>
                <div>
                    <div className="text-[10px] font-bold uppercase tracking-widest text-slate-500">Total Structural Value Summary</div>
                    <div className="text-sm font-mono font-bold text-emerald-400">{formatISK(grandTotalPrice)} ISK</div>
                </div>
            </div>

            {/* Quick UX Toolbar */}
            <div className="p-3 bg-slate-950/20 border-b border-slate-800/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div className="w-full sm:w-72 relative">
                    <span className="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-500 text-xs">🔍</span>
                    <input
                        type="text"
                        placeholder="Search item specifications..."
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        className="w-full bg-slate-950/80 border border-slate-800 focus:border-indigo-500 rounded-xl text-xs text-slate-300 ps-8 pe-3 py-2 focus:outline-none transition-colors placeholder-slate-600"
                    />
                    {searchTerm && (
                        <button type="button" onClick={() => setSearchTerm('')} className="absolute inset-y-0 end-0 flex items-center pe-3 text-slate-500 hover:text-slate-300 text-xs font-bold">✕</button>
                    )}
                </div>

                {buildMode === 'tree' && (
                    <div className="flex flex-wrap items-center gap-2">
                        <span className="text-[10px] font-black uppercase tracking-wider text-slate-500 mr-1">Bulk Operations:</span>
                        <button type="button" onClick={() => onBulkDeconstruct(true)} className="bg-slate-900 border border-slate-800/80 hover:border-slate-700 hover:bg-slate-800/60 text-slate-300 px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors">Deconstruct All</button>
                        <button type="button" onClick={() => onBulkDeconstruct(false)} className="bg-slate-900 border border-slate-800/80 hover:border-slate-700 hover:bg-slate-800/60 text-slate-400 px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors">Raw Buy All</button>
                        <div className="w-[1px] h-4 bg-slate-800 mx-1" />
                        <button type="button" onClick={() => onBulkCollapse(false)} className="bg-slate-900 border border-slate-800/80 hover:border-slate-700 hover:bg-slate-800/60 text-slate-300 px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors">Expand All</button>
                        <button type="button" onClick={() => onBulkCollapse(true)} className="bg-slate-900 border border-slate-800/80 hover:border-slate-700 hover:bg-slate-800/60 text-slate-400 px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors">Collapse All</button>
                    </div>
                )}
            </div>

            <div className="overflow-x-auto w-full">
                <table className="w-full text-left border-collapse">
                    <thead>
                        <tr className="border-b border-slate-800 bg-slate-950/20 text-[10px] font-black uppercase tracking-wider text-slate-400 select-none">
                            <th className="py-3 px-4">Material Specification Type</th>
                            <th className="py-3 px-4 text-center">Required Units</th>
                            <th className="py-3 px-4 text-right">Volume</th>
                            <th className="py-3 px-4 text-right">Estimated Cost Baseline</th>
                            {buildMode === 'tree' && <th className="py-3 px-4 text-center">Industrial Operations</th>}
                        </tr>
                    </thead>
                    <tbody>
                        {materials.map(material => renderRow(material, 0))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
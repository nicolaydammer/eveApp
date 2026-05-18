import React from 'react';
import { formatNum, formatISK, getScaledQuantity } from '@/Components/helpers/industryUtils.js';

export default function FullTreeTable({
    materials, deconStates, onToggleDecon, collapsedNodes, onToggleCollapse, batchMultiplier, materialModifier
}) {
    const RenderTreeRows = ({ nodes, depth = 0 }) => {
        return (
            <>
                {nodes.map((node) => {
                    const hasChildren = node.children && node.children.length > 0;
                    const isDeconstructed = !!deconStates[node.typeID];
                    const isCollapsed = !!collapsedNodes[node.typeID];
                    const scaledQty = getScaledQuantity(node.quantity, batchMultiplier, materialModifier);
                    const totalVol = (node.unit_volume || 0) * scaledQty;
                    const estPrice = node.estimated_price || 0;

                    return (
                        <React.Fragment key={node.typeID}>
                            <tr className={`border-b border-slate-900/30 text-slate-300 hover:bg-slate-900/40 ${isDeconstructed ? 'bg-slate-900/10' : ''}`}>
                                {/* DECON Active Checkbox Selection */}
                                <td className="px-6 py-3.5 text-center w-16">
                                    {hasChildren && (
                                        <input
                                            type="checkbox" checked={isDeconstructed} onChange={() => onToggleDecon(node.typeID)}
                                            className="w-4 h-4 rounded border-slate-800 bg-slate-950 text-indigo-600 focus:ring-0 cursor-pointer"
                                        />
                                    )}
                                </td>

                                {/* Resource Stacked Meta Title Layout Column */}
                                <td className="px-4 py-3.5" style={{ paddingLeft: `${Math.max(16, depth * 32)}px` }}>
                                    <div className="flex items-center gap-3">
                                        {hasChildren && (
                                            <button onClick={() => onToggleCollapse(node.typeID)} className="text-slate-500 hover:text-slate-200 text-xs focus:outline-none shrink-0 w-4">
                                                {isCollapsed ? '▶' : '▼'}
                                            </button>
                                        )}
                                        {!hasChildren && <span className="text-slate-600 font-mono text-xs w-4 text-center">└</span>}

                                        <img src={`https://images.evetech.net/types/${node.typeID}/icon?size=32`} alt="" className="w-8 h-8 bg-slate-950 rounded-xl border border-slate-800 shrink-0" onError={(e) => { e.target.src = 'https://images.evetech.net/types/0/icon?size=32'; }} />

                                        <div className="truncate">
                                            <span className={`block truncate leading-snug ${hasChildren ? 'font-bold text-slate-200' : 'text-slate-300 font-medium'}`}>
                                                {node.name}
                                            </span>
                                            <span className="text-[10px] text-slate-500 font-mono block">ID: {node.typeID}</span>
                                        </div>
                                    </div>
                                </td>

                                <td className="px-4 py-3.5 text-right font-mono font-bold text-slate-300">{formatNum(scaledQty)}</td>
                                <td className="px-4 py-3.5 text-right font-mono text-indigo-400 font-semibold">{isDeconstructed ? <span className="text-slate-600">-</span> : `${formatNum(totalVol)} m³`}</td>
                                <td className="px-4 py-3.5 text-right font-mono text-emerald-400 font-bold pr-8">{isDeconstructed ? <span className="text-slate-600">-</span> : `${formatISK(estPrice * scaledQty)}`}</td>
                            </tr>

                            {hasChildren && isDeconstructed && !isCollapsed && (
                                <RenderTreeRows nodes={node.children} depth={depth + 1} />
                            )}
                        </React.Fragment>
                    );
                })}
            </>
        );
    };

    const hullMaterials = materials.filter(m => !m.children || m.children.length === 0);
    const complexComponents = materials.filter(m => m.children && m.children.length > 0);

    return (
        <div className="w-full overflow-x-auto">
            <table className="w-full text-left border-collapse table-fixed min-w-[900px]">
                <thead>
                    <tr className="bg-slate-900/20 border-b border-slate-800/80 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <th className="w-[8%] px-6 py-4 text-center">Decon</th>
                        <th className="w-[42%] px-4 py-4 text-left">Resource Hierarchy</th>
                        <th className="w-[12%] px-4 py-4 text-right">Qty</th>
                        <th className="w-[15%] px-4 py-4 text-right">Vol (m³)</th>
                        <th className="w-[23%] px-4 py-4 text-right pr-8">Subtotal</th>
                    </tr>
                </thead>
                <tbody className="text-sm">
                    {hullMaterials.length > 0 && (
                        <>
                            <tr className="bg-slate-900/30 border-b border-slate-900 font-bold text-[10px] tracking-wider text-indigo-400 uppercase">
                                <td colSpan="5" className="px-6 py-2.5 font-black tracking-widest">HULL MATERIALS</td>
                            </tr>
                            <RenderTreeRows nodes={hullMaterials} />
                        </>
                    )}
                    {complexComponents.length > 0 && (
                        <>
                            <tr className="bg-slate-900/30 border-y border-slate-900 font-bold text-[10px] tracking-wider text-indigo-400 uppercase">
                                <td colSpan="5" className="px-6 py-2.5 font-black tracking-widest">COMPONENT BUILD CHAIN</td>
                            </tr>
                            <RenderTreeRows nodes={complexComponents} />
                        </>
                    )}
                </tbody>
            </table>
        </div>
    );
}
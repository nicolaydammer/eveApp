import React from 'react';
import { formatNum, formatISK, getScaledQuantity } from '@/Components/helpers/industryUtils.js';

export default function DirectBuyTable({
    groupedMaterials, batchMultiplier, materialModifier, grandTotalVolume, grandTotalPrice
}) {
    return (
        <div className="w-full overflow-x-auto">
            <table className="w-full text-left border-collapse table-fixed min-w-[900px]">
                <thead>
                    <tr className="bg-slate-900/20 border-b border-slate-800/80 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <th className="w-[30%] px-6 py-4 text-left">Item Resource</th>
                        <th className="w-[12%] px-4 py-4 text-right">Needed Qty</th>
                        <th className="w-[13%] px-4 py-4 text-right">Unit Vol</th>
                        <th className="w-[15%] px-4 py-4 text-right">Total Vol</th>
                        <th className="w-[15%] px-4 py-4 text-right text-slate-500">Est. Price</th>
                        <th className="w-[15%] px-4 py-4 text-right pr-8 text-slate-500">Total Price</th>
                    </tr>
                </thead>
                <tbody className="divide-y divide-slate-900/60 text-sm">
                    {Object.entries(groupedMaterials).map(([groupName, items]) => (
                        <React.Fragment key={groupName}>
                            <tr className="bg-slate-900/30 border-y border-slate-900 font-bold text-xs uppercase tracking-wider">
                                <td colSpan="6" className="px-6 py-2.5 text-indigo-400 font-semibold tracking-wide">
                                    <span className="bg-slate-900/80 border border-slate-800/80 px-2.5 py-1 rounded-md">{groupName}</span>
                                </td>
                            </tr>
                            {items.map((item) => {
                                const scaledQuantity = getScaledQuantity(item.quantity, batchMultiplier, materialModifier);
                                const totalVolume = (item.unit_volume || 0) * scaledQuantity;
                                const estPrice = item.estimated_price || 0;
                                return (
                                    <tr key={item.typeID} className="hover:bg-slate-900/40 border-b border-slate-900/30 text-slate-300">
                                        <td className="px-6 py-3.5 flex items-center gap-4">
                                            <img src={`https://images.evetech.net/types/${item.typeID}/icon?size=32`} alt="" className="w-8 h-8 bg-slate-950 rounded-xl border border-slate-800 shrink-0" onError={(e) => { e.target.src = 'https://images.evetech.net/types/0/icon?size=32'; }} />
                                            <div className="truncate">
                                                <span className="font-semibold text-slate-200 block truncate leading-snug">{item.name}</span>
                                                <span className="text-[10px] text-slate-500 font-mono tracking-wide">ID: {item.typeID}</span>
                                            </div>
                                        </td>
                                        <td className="px-4 py-3.5 text-right font-mono font-bold text-slate-300">{formatNum(scaledQuantity)}</td>
                                        <td className="px-4 py-3.5 text-right font-mono text-slate-500 text-xs">{formatNum(item.unit_volume)} m³</td>
                                        <td className="px-4 py-3.5 text-right font-mono text-indigo-400 font-semibold">{formatNum(totalVolume)} m³</td>
                                        <td className="px-4 py-3.5 text-right font-mono text-slate-600 text-xs">{formatISK(estPrice)} ISK</td>
                                        <td className="px-4 py-3.5 text-right font-mono text-emerald-400 font-bold pr-8">{formatISK(estPrice * scaledQuantity)}</td>
                                    </tr>
                                );
                            })}
                        </React.Fragment>
                    ))}
                </tbody>
                <tfoot>
                    <tr className="bg-slate-900/40 border-t-2 border-slate-800 font-bold text-sm text-slate-200">
                        <td colSpan="3" className="px-6 py-5 text-right font-bold text-xs uppercase tracking-widest text-slate-400">Total Volume:</td>
                        <td className="px-4 py-5 text-right font-mono text-indigo-400 font-black">{formatNum(grandTotalVolume)} m³</td>
                        <td className="px-4 py-5 text-right font-bold text-xs uppercase tracking-widest text-slate-400">Total ISK:</td>
                        <td className="px-4 py-5 text-right font-mono text-emerald-400 font-black pr-8">{formatISK(grandTotalPrice)}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    );
}
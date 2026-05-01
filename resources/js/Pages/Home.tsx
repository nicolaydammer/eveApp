import { route } from 'ziggy-js';
import React, { useState, useMemo } from 'react';
import {
    ChevronDown,
    ChevronRight,
    Box,
    Anchor,
    Zap,
    Truck,
    AlertTriangle,
    Settings,
    Scale,
    Layers
} from 'lucide-react';

const AdvancedIndustryCalculator = () => {
    const [calcMode, setCalcMode] = useState('raw');
    const [manufactureSet, setManufactureSet] = useState(new Set(['c1', 'sub1']));
    const [expanded, setExpanded] = useState(new Set(['c1', 'sub1']));

    const productionTree = {
        hull: "Thanatos",
        hullMinerals: [
            { id: 'h1', name: 'Tritanium', qty: 52000000, jita: 4.1, vol: 0.01 },
            { id: 'h2', name: 'Zydrine', qty: 22000, jita: 1350, vol: 0.01 },
        ],
        components: [
            {
                id: 'c1',
                name: 'Capital Armor Plating',
                qty: 12,
                vol: 1000,
                jita: 16800000,
                children: [
                    {
                        id: 'sub1',
                        name: 'Construction Blocks',
                        qty: 250,
                        vol: 5,
                        jita: 45000,
                        children: [
                            { id: 'm1', name: 'Pyerite', qty: 1200, jita: 11.4, vol: 0.01 },
                            { id: 'm2', name: 'Mexallon', qty: 450, jita: 68, vol: 0.01 },
                        ]
                    },
                    { id: 'm3', name: 'Isogen', qty: 8500, jita: 485, vol: 0.01 }
                ]
            }
        ]
    };

    const totals = useMemo(() => {
        let totalISK = 0; let totalVol = 0;
        productionTree.hullMinerals.forEach(m => {
            totalISK += (m.qty * m.jita);
            totalVol += (m.qty * m.vol);
        });
        productionTree.components.forEach(comp => {
            const isBuildingComp = manufactureSet.has(comp.id);
            if (!isBuildingComp || calcMode === 'direct') {
                totalISK += (comp.qty * comp.jita); totalVol += (comp.qty * comp.vol);
            } else {
                comp.children.forEach(child => {
                    const isBuildingSub = manufactureSet.has(child.id);
                    const totalChildQty = child.qty * comp.qty;
                    if (!isBuildingSub || !child.children) {
                        totalISK += (totalChildQty * child.jita); totalVol += (totalChildQty * child.vol);
                    } else {
                        child.children.forEach(gc => {
                            const totalGcQty = gc.qty * totalChildQty;
                            totalISK += (totalGcQty * gc.jita); totalVol += (totalGcQty * gc.vol);
                        });
                    }
                });
            }
        });
        return { totalISK, totalVol };
    }, [manufactureSet, calcMode]);

    const toggleManufacture = (id: string) => {
        const next = new Set(manufactureSet);
        next.has(id) ? next.delete(id) : next.add(id);
        setManufactureSet(next);
    };

    const toggleExpand = (id: string) => {
        const next = new Set(expanded);
        next.has(id) ? next.delete(id) : next.add(id);
        setExpanded(next);
    };

    return (
        <div className="min-h-screen bg-[#06080a] text-slate-300 font-sans grid grid-cols-12">

            {/* SIDEBAR */}
            <section className="col-span-4 bg-[#0b0e11] border-r border-slate-800 p-12 flex flex-col justify-between h-screen sticky top-0 overflow-y-auto">
                <div>
                    <div className="flex items-center gap-4 mb-10">
                        <div className="h-12 w-12 bg-blue-600 rounded-lg flex items-center justify-center shadow-[0_0_20px_rgba(37,99,235,0.3)]">
                            <Settings className="text-white" size={28} />
                        </div>
                        <div>
                            <h1 className="text-xl font-black tracking-tighter leading-none text-white uppercase italic">Advanced Industry</h1>
                            <p className="text-blue-500 font-bold text-[10px] tracking-[0.3em] uppercase">Calculator</p>
                        </div>
                    </div>

                    <div className="space-y-8 mb-12">
                        <div className="flex gap-4">
                            <div className="p-5 bg-blue-500/10 rounded-lg text-blue-400 shrink-0"><Layers size={20} /></div>
                            <div>
                                <h4 className="text-sm font-bold text-slate-200">Supply Chain Batching</h4>
                                <p className="text-xs text-slate-500 leading-relaxed text-balance">Define production runs based on your facility limits. Calculate the exact materials needed for partial builds or mass production.</p>
                            </div>
                        </div>

                        <div className="flex gap-4">
                            <div className="p-5 bg-purple-500/10 rounded-lg text-purple-400 shrink-0"><Scale size={20} /></div>
                            <div>
                                <h4 className="text-sm font-bold text-slate-200">Compression Ratios</h4>
                                <p className="text-xs text-slate-500 leading-relaxed text-balance">View the volumetric delta between ores, compressed blocks, and finished components to optimize jump freighter fuel efficiency.</p>
                            </div>
                        </div>

                        <div className="flex gap-4">
                            <div className="p-5 bg-emerald-500/10 rounded-lg text-emerald-400 shrink-0"><Truck size={20} /></div>
                            <div>
                                <h4 className="text-sm font-bold text-slate-200">Logistics Planning</h4>
                                <p className="text-xs text-slate-500 leading-relaxed text-balance">Toggle manufacturing stages to instantly see if your buy-list fits in an Obelisk or if you need a dedicated logistics wing.</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-blue-500/10 border border-blue-500/30 p-5 rounded-xl">
                        <div className="flex items-center gap-2 text-blue-500 mb-2">
                            <AlertTriangle color="red" size={18} />
                            <span className="text-xs font-bold uppercase tracking-tight">Technical Demo</span>
                        </div>
                        <p className="text-[11px] text-blue-200/70 leading-relaxed italic">
                            This showcase highlights the <strong>Recursive Cut-off Logic</strong> and uses dummy data. The actual application is more advanced and has a more sophisticated user interface.
                        </p>
                    </div>
                </div>

                <div className="pt-8 border-t border-slate-800" onClick={() => window.location.href = route('auth.redirectToEveSSO')}>
                    <img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" className="w-52 cursor-pointer hover:brightness-110 active:scale-95 transition" alt="SSO" />
                </div>
            </section >

            {/* MAIN CONTENT */}
            < section className="col-span-8 p-12 bg-gradient-to-br from-[#06080a] to-[#0d121d] overflow-y-auto" >
                <div className="max-w-4xl mx-auto">

                    <div className="flex items-end justify-between mb-10 border-b border-slate-800 pb-8">
                        <div className="flex items-center gap-6">
                            <div className="w-20 h-20 bg-slate-900 rounded-2xl border border-slate-700 p-2 shadow-2xl relative overflow-hidden">
                                <img src="https://images.evetech.net/types/23911/render?size=256" alt="Thanatos" className="relative z-10" />
                                <div className="absolute inset-0 bg-blue-500/10 blur-xl" />
                            </div>
                            <h2 className="text-4xl font-bold text-white tracking-tighter uppercase leading-none">Thanatos</h2>
                        </div>

                        <div className="flex bg-[#111419] p-1 rounded-xl border border-slate-700 shadow-inner">
                            <button onClick={() => setCalcMode('direct')} className={`px-5 py-2 text-[10px] font-black rounded-lg transition ${calcMode === 'direct' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-500 hover:text-white'}`}>DIRECT BUY</button>
                            <button onClick={() => setCalcMode('raw')} className={`px-5 py-2 text-[10px] font-black rounded-lg transition ${calcMode === 'raw' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-500 hover:text-white'}`}>FULL TREE</button>
                        </div>
                    </div>

                    <div className="bg-[#111419]/50 rounded-2xl border border-slate-800 overflow-hidden shadow-2xl">
                        <table className="w-full text-sm text-left">
                            <thead>
                                <tr className="bg-slate-900 text-[10px] font-bold uppercase text-slate-500 tracking-widest border-b border-slate-800">
                                    {/* HIDDEN CHECKBOX HEADER IN DIRECT BUY */}
                                    {calcMode === 'raw' && <th className="p-4 w-12 text-center">Decon</th>}
                                    <th className="p-4">Resource Hierarchy</th>
                                    <th className="p-4 text-right">Qty</th>
                                    <th className="p-4 text-right">Vol (m³)</th>
                                    <th className="p-4 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-800/50">
                                {/* HULL MATERIALS */}
                                <tr className="bg-slate-900/40"><td colSpan={calcMode === 'raw' ? 5 : 4} className="px-4 py-2 text-[10px] text-blue-500 font-black uppercase tracking-widest italic">Hull Materials</td></tr>
                                {productionTree.hullMinerals.map(m => (
                                    <tr key={m.id} className="hover:bg-white/5 transition-colors group">
                                        {calcMode === 'raw' && <td className="p-4 text-center"><Anchor size={12} className="text-slate-700 mx-auto" /></td>}
                                        <td className={`p-4 text-slate-300 font-medium italic ${calcMode === 'direct' ? 'pl-4' : 'pl-8'}`}>{m.name}</td>
                                        <td className="p-4 text-right font-mono text-xs">{m.qty.toLocaleString()}</td>
                                        <td className="p-4 text-right font-mono text-xs">{(m.qty * m.vol).toLocaleString()}</td>
                                        <td className="p-4 text-right font-mono text-emerald-500 font-bold">{(m.qty * m.jita / 1000000).toFixed(1)}M</td>
                                    </tr>
                                ))}

                                {/* COMPONENT BUILD CHAIN */}
                                <tr className="bg-slate-900/40"><td colSpan={calcMode === 'raw' ? 5 : 4} className="px-4 py-2 text-[10px] text-blue-500 font-black uppercase tracking-widest italic">Component Build Chain</td></tr>
                                {productionTree.components.map(comp => {
                                    const isDecon = manufactureSet.has(comp.id) && calcMode === 'raw';
                                    const isExpanded = expanded.has(comp.id) && calcMode === 'raw';
                                    return (
                                        <React.Fragment key={comp.id}>
                                            <tr className={`hover:bg-white/5 transition-colors ${isDecon ? 'bg-white/5' : ''}`}>
                                                {calcMode === 'raw' && (
                                                    <td className="p-4 text-center">
                                                        <input type="checkbox" checked={isDecon} onChange={() => toggleManufacture(comp.id)} className="w-4 h-4 rounded accent-blue-600" />
                                                    </td>
                                                )}
                                                <td className="p-4 font-bold text-white flex items-center gap-2 cursor-pointer" onClick={() => calcMode === 'raw' && toggleExpand(comp.id)}>
                                                    {calcMode === 'raw' ? (isExpanded ? <ChevronDown size={14} className="text-blue-500" /> : <ChevronRight size={14} className="text-slate-600" />) : <Box size={14} className="text-blue-500" />}
                                                    {comp.name}
                                                </td>
                                                <td className="p-4 text-right font-mono">{comp.qty}</td>
                                                <td className="p-4 text-right font-mono text-xs text-slate-500">{isDecon ? '-' : (comp.qty * comp.vol).toLocaleString()}</td>
                                                <td className="p-4 text-right font-mono text-emerald-400 font-bold tracking-tighter">{isDecon ? '-' : (comp.qty * comp.jita / 1000000).toFixed(1) + 'M'}</td>
                                            </tr>

                                            {isExpanded && comp.children.map(sub => {
                                                const isDeconSub = manufactureSet.has(sub.id) && isDecon;
                                                const isSubExpanded = expanded.has(sub.id);
                                                const totalSubQty = sub.qty * comp.qty;
                                                return (
                                                    <React.Fragment key={sub.id}>
                                                        <tr className="bg-blue-500/[0.02] border-l-2 border-blue-500/50">
                                                            <td className="p-4 text-center">
                                                                {sub.children && isDecon && <input type="checkbox" checked={isDeconSub} onChange={() => toggleManufacture(sub.id)} className="w-3 h-3 rounded accent-blue-500" />}
                                                            </td>
                                                            <td className="p-4 pl-10 text-xs font-semibold text-slate-300 italic flex items-center gap-2 cursor-pointer" onClick={() => sub.children && toggleExpand(sub.id)}>
                                                                {sub.children ? (isSubExpanded ? <ChevronDown size={12} /> : <ChevronRight size={12} />) : <Anchor size={10} className="text-slate-700" />}
                                                                {sub.name}
                                                            </td>
                                                            <td className="p-4 text-right font-mono text-xs text-slate-500">{totalSubQty.toLocaleString()}</td>
                                                            <td className="p-4 text-right font-mono text-[10px] text-slate-600">{isDeconSub ? '-' : (totalSubQty * sub.vol).toLocaleString()}</td>
                                                            <td className="p-4 text-right font-mono text-[10px] text-emerald-600">{isDeconSub ? '-' : (totalSubQty * sub.jita / 1000000).toFixed(2) + 'M'}</td>
                                                        </tr>
                                                        {isDeconSub && isSubExpanded && sub.children?.map(m => (
                                                            <tr key={m.id} className="bg-blue-500/[0.04] border-l-4 border-blue-500/10">
                                                                <td></td>
                                                                <td className="p-4 pl-20 text-[10px] text-slate-500 italic flex items-center gap-2">
                                                                    <div className="w-1 h-1 bg-slate-800 rounded-full" /> {m.name}
                                                                </td>
                                                                <td className="p-4 text-right font-mono text-[10px] text-slate-600">{(m.qty * totalSubQty).toLocaleString()}</td>
                                                                <td className="p-4 text-right font-mono text-[10px] text-slate-600">{(m.qty * totalSubQty * m.vol).toLocaleString()}</td>
                                                                <td className="p-4 text-right font-mono text-[10px] text-emerald-900 font-bold">{(m.qty * totalSubQty * m.jita / 1000000).toFixed(3)}M</td>
                                                            </tr>
                                                        ))}
                                                    </React.Fragment>
                                                );
                                            })}
                                        </React.Fragment>
                                    );
                                })}
                            </tbody>
                        </table>
                    </div>

                    <div className="mt-10 grid grid-cols-2 gap-6">
                        <div className="bg-[#111419] p-6 rounded-2xl border border-slate-800 flex justify-between items-center group">
                            <div>
                                <p className="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 text-balance">Logistics Volume Needed</p>
                                <p className="text-3xl font-mono font-black text-blue-400">{(totals.totalVol).toLocaleString()} m³</p>
                            </div>
                            <Truck className="text-slate-700 group-hover:text-blue-500 transition-colors" size={32} />
                        </div>
                        <div className="bg-[#111419] p-6 rounded-2xl border border-slate-800 flex justify-between items-center group">
                            <div>
                                <p className="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 text-balance">Project ISK Capital Required</p>
                                <p className="text-3xl font-mono font-black text-emerald-400">{(totals.totalISK / 1000000000).toFixed(2)} B</p>
                            </div>
                            <Zap className="text-slate-700 group-hover:text-emerald-500 transition-colors" size={32} />
                        </div>
                    </div>
                </div>
            </section>
        </div >
    );
};

export default AdvancedIndustryCalculator;
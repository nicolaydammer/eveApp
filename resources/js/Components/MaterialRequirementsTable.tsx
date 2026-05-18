import React, { useState, useEffect } from 'react';
import axios from 'axios';

export default function MaterialRequirementsTable({ blueprintKey }) {
    const [buildMode, setBuildMode] = useState('direct'); // 'direct' or 'tree'
    const [runs, setRuns] = useState(1);
    const [me, setMe] = useState(0);
    const [te, setTe] = useState(0);
    const [payload, setPayload] = useState(null);
    const [loading, setLoading] = useState(false);

    // UI State for Copy Indicators
    const [copied, setCopied] = useState(false);
    const [skillsCopied, setSkillsCopied] = useState(false);

    // Modal Control States
    const [isStructureModalOpen, setIsStructureModalOpen] = useState(false);
    const [isSkillsModalOpen, setIsSkillsModalOpen] = useState(false);

    // Facility State
    const [structureType, setStructureType] = useState('sotiyo');
    const [rigTier, setRigTier] = useState('t2');

    useEffect(() => {
        if (!blueprintKey) return;

        const loadData = async () => {
            setLoading(true);
            const routeName = buildMode === 'tree' ? 'industry.fullTree' : 'industry.directBuy';

            try {
                const targetUrl = route(routeName, { _key: blueprintKey });
                const response = await axios.get(targetUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                setPayload(response.data);
            } catch (error) {
                console.error("Failed parsing pipeline payload:", error);
                setPayload(null);
            } finally {
                setLoading(false);
            }
        };

        loadData();
    }, [blueprintKey, buildMode]);

    const batchMultiplier = Math.max(1, parseInt(runs) || 1);
    const materialModifier = Math.min(10, Math.max(0, parseInt(me) || 0)) / 100;
    const timeModifier = Math.min(10, Math.max(0, parseInt(te) || 0)) / 100;

    const materials = buildMode === 'direct'
        ? (payload?.manufacturing?.materials || [])
        : (payload?.requirements?.materials || []);

    const skills = payload?.skills || [];

    // Dynamically look up time reduction skill multipliers from the blueprint prerequisites
    const industrySkillLevel = skills.find(s => s.name === 'Industry')?.level || 0;
    const advIndustrySkillLevel = skills.find(s => s.name === 'Advanced Industry')?.level || 0;

    // Industry provides 4% reduction per level, Advanced Industry provides 3% reduction per level
    const industryReductionFactor = 1 - (industrySkillLevel * 0.04);
    const advIndustryReductionFactor = 1 - (advIndustrySkillLevel * 0.03);

    const baseProductionTime = buildMode === 'direct'
        ? (payload?.manufacturing?.time || 0)
        : (payload?.requirements?.time || 0);

    // Calculate duration applying batch runs, TE configurations, and active skill parameters
    const totalDurationSeconds = baseProductionTime * batchMultiplier * (1 - timeModifier) * industryReductionFactor * advIndustryReductionFactor;

    // EVE Standard ME scaling formula
    const getScaledQuantity = (baseQty) => {
        if (!baseQty) return 0;
        return Math.max(batchMultiplier, Math.ceil(baseQty * batchMultiplier * (1 - materialModifier)));
    };

    const formatDuration = (totalSeconds) => {
        if (totalSeconds <= 0) return '0s';
        const days = Math.floor(totalSeconds / 86400);
        const hours = Math.floor((totalSeconds % 86400) / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = Math.floor(totalSeconds % 60);

        let result = [];
        if (days > 0) result.push(`${days}d`);
        if (hours > 0) result.push(`${hours}h`);
        if (minutes > 0) result.push(`${minutes}m`);
        if (seconds > 0 || result.length === 0) result.push(`${seconds}s`);
        return result.join(' ');
    };

    const groupedMaterials = materials.reduce((acc, item) => {
        const group = item.group_name || "Production Components";
        if (!acc[group]) acc[group] = [];
        acc[group].push(item);
        return acc;
    }, {});

    const grandTotalVolume = materials.reduce((sum, item) => {
        return sum + ((item.unit_volume || 0) * getScaledQuantity(item.quantity));
    }, 0);

    const grandTotalPrice = 0;

    // Clipboard handlers
    const handleCopyToClipboard = () => {
        if (materials.length === 0) return;
        const multibuyText = materials.map(item => `${item.name} ${getScaledQuantity(item.quantity)}`).join('\n');
        navigator.clipboard.writeText(multibuyText)
            .then(() => { setCopied(true); setTimeout(() => setCopied(false), 2000); });
    };

    const handleCopySkillsToClipboard = () => {
        if (skills.length === 0) return;
        const skillsText = skills.map(skill => `${skill.name} Level ${skill.level}`).join('\n');
        navigator.clipboard.writeText(skillsText)
            .then(() => { setSkillsCopied(true); setTimeout(() => setSkillsCopied(false), 2000); });
    };

    const formatNum = (num) => new Intl.NumberFormat().format(num);
    const formatISK = (num) => new Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);

    return (
        <div className="space-y-4 w-full text-slate-200 relative">

            {/* Top Control Header Panel */}
            <div className="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">

                {/* Configuration Controls Bar */}
                <div className="flex flex-wrap items-center gap-3 w-full xl:w-auto">

                    {/* 1. Mode Switcher */}
                    <div className="inline-flex bg-slate-900/90 p-1 rounded-xl border border-slate-800 shadow-lg shrink-0">
                        <button
                            type="button"
                            onClick={() => setBuildMode('direct')}
                            className={`px-4 py-2 text-xs font-bold tracking-wide uppercase rounded-lg transition-all duration-200 ${buildMode === 'direct' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200'
                                }`}
                        >
                            Direct Buy
                        </button>
                        <button
                            type="button"
                            onClick={() => setBuildMode('tree')}
                            className={`px-4 py-2 text-xs font-bold tracking-wide uppercase rounded-lg transition-all duration-200 ${buildMode === 'tree' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200'
                                }`}
                        >
                            Full Tree
                        </button>
                    </div>

                    {/* Dialog Triggers without Emojis */}
                    <div className="flex gap-2">
                        <button
                            type="button"
                            onClick={() => setIsStructureModalOpen(true)}
                            className="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 px-4 py-2 text-xs font-bold rounded-xl uppercase tracking-wider shadow-lg transition-all h-[42px] flex items-center"
                        >
                            Structure Settings
                        </button>

                        <button
                            type="button"
                            onClick={() => setIsSkillsModalOpen(true)}
                            className="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 px-4 py-2 text-xs font-bold rounded-xl uppercase tracking-wider shadow-lg transition-all h-[42px] flex items-center"
                        >
                            Required Skills ({skills.length})
                        </button>
                    </div>

                    {/* 2. Runs / ME / TE Modifiers */}
                    <div className="flex flex-wrap items-center gap-3 bg-slate-900/90 rounded-xl border border-slate-800 p-1 shadow-lg">
                        <div className="flex items-center px-2 py-1">
                            <label htmlFor="bpo-runs" className="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">Runs:</label>
                            <input
                                id="bpo-runs" type="number" min="1" value={runs}
                                onChange={(e) => setRuns(e.target.value)}
                                className="w-14 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-md text-center font-mono font-bold text-xs text-indigo-400 p-1 focus:outline-none [appearance:textfield]"
                            />
                        </div>
                        <div className="w-[1px] h-5 bg-slate-800" />
                        <div className="flex items-center px-2 py-1">
                            <label htmlFor="bpo-me" className="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">ME %:</label>
                            <input
                                id="bpo-me" type="number" min="0" max="10" value={me}
                                onChange={(e) => setMe(e.target.value)}
                                className="w-12 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-md text-center font-mono font-bold text-xs text-emerald-400 p-1 focus:outline-none [appearance:textfield]"
                            />
                        </div>
                        <div className="w-[1px] h-5 bg-slate-800" />
                        <div className="flex items-center px-2 py-1">
                            <label htmlFor="bpo-te" className="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">TE %:</label>
                            <input
                                id="bpo-te" type="number" min="0" max="10" value={te}
                                onChange={(e) => setTe(e.target.value)}
                                className="w-12 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-md text-center font-mono font-bold text-xs text-amber-400 p-1 focus:outline-none [appearance:textfield]"
                            />
                        </div>
                    </div>
                </div>

                {/* Top Action Copy Button */}
                {buildMode === 'direct' && materials.length > 0 && (
                    <button
                        type="button"
                        onClick={handleCopyToClipboard}
                        disabled={loading}
                        className={`inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-xl border uppercase tracking-wider shadow-lg transition-all duration-150 h-[42px] shrink-0 w-full xl:w-auto justify-center ${copied ? 'bg-emerald-950/40 border-emerald-500 text-emerald-400' : 'bg-slate-900 border-slate-800 hover:bg-slate-800 text-indigo-400 hover:text-indigo-300'
                            }`}
                    >
                        {copied ? '✓ Copied Layout' : '📋 Copy Multibuy'}
                    </button>
                )}
            </div>

            {/* Core Output Table Grid Container */}
            <div className="border border-slate-800/80 rounded-2xl bg-slate-950/40 shadow-2xl overflow-hidden relative min-h-[160px] backdrop-blur-md">
                {loading && (
                    <div className="absolute inset-0 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center z-10">
                        <svg className="animate-spin h-9 w-9 text-indigo-500" fill="none" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" /><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" /></svg>
                    </div>
                )}

                <div className="bg-slate-900/60 px-5 py-3.5 border-b border-slate-800/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div className="flex items-center gap-2">
                        <span className="w-2 h-2 rounded-full bg-indigo-500 shadow-glow animate-pulse" />
                        <h3 className="text-xs font-bold text-slate-400 uppercase tracking-widest">Required Materials</h3>
                    </div>
                    <div className="flex flex-wrap items-center gap-2">
                        {baseProductionTime > 0 && (
                            <span className="text-xs bg-slate-950 px-3 py-1 rounded-lg font-mono text-amber-400 font-bold border border-slate-800 shadow-sm">
                                Total Time: {formatDuration(totalDurationSeconds)}
                                {(industrySkillLevel > 0 || advIndustrySkillLevel > 0) && (
                                    <span className="text-[10px] text-slate-400 font-normal ml-1">
                                        (Skills: Ind {industrySkillLevel}, Adv {advIndustrySkillLevel})
                                    </span>
                                )}
                            </span>
                        )}
                        {payload?.name && (
                            <span className="text-xs bg-slate-900 px-3 py-1 rounded-lg font-mono text-indigo-400 font-bold border border-slate-800 shadow-sm">
                                {payload.name} {batchMultiplier > 1 && `(x${batchMultiplier})`}
                            </span>
                        )}
                    </div>
                </div>

                {materials.length === 0 && !loading ? (
                    <div className="p-12 text-center text-slate-500 text-sm font-medium tracking-wide">No data found.</div>
                ) : (
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
                                            const scaledQuantity = getScaledQuantity(item.quantity);
                                            const totalVolume = (item.unit_volume || 0) * scaledQuantity;
                                            return (
                                                <tr key={item.typeID} className="hover:bg-slate-900/40 border-b border-slate-900/30 text-slate-300">
                                                    <td className="px-6 py-3.5 flex items-center gap-4">
                                                        <img src={`https://images.evetech.net/types/${item.typeID}/icon?size=32`} alt={item.name} className="w-8 h-8 bg-slate-950 rounded-xl border border-slate-800 shrink-0" onError={(e) => { e.target.src = 'https://images.evetech.net/types/0/icon?size=32'; }} />
                                                        <div className="truncate">
                                                            <span className="font-semibold text-slate-200 block truncate leading-snug">{item.name}</span>
                                                            <span className="text-[10px] text-slate-500 font-mono tracking-wide">ID: {item.typeID}</span>
                                                        </div>
                                                    </td>
                                                    <td className="px-4 py-3.5 text-right font-mono font-bold text-slate-300">{formatNum(scaledQuantity)}</td>
                                                    <td className="px-4 py-3.5 text-right font-mono text-slate-500 text-xs">{formatNum(item.unit_volume)} m³</td>
                                                    <td className="px-4 py-3.5 text-right font-mono text-indigo-400 font-semibold">{formatNum(totalVolume)} m³</td>
                                                    <td className="px-4 py-3.5 text-right font-mono text-slate-600 text-xs">0.00 ISK</td>
                                                    <td className="px-4 py-3.5 text-right font-mono text-slate-600 text-xs pr-8">0.00 ISK</td>
                                                </tr>
                                            );
                                        })}
                                    </React.Fragment>
                                ))}
                            </tbody>
                            <tfoot className="border-t-2 border-slate-800 bg-slate-900/40 font-bold text-sm text-slate-200">
                                <tr>
                                    <td className="px-6 py-5 text-left text-slate-400 uppercase text-xs font-black">Total Calculation</td>
                                    <td colSpan="2" className="px-4 py-5" />
                                    <td className="px-4 py-5 text-right font-mono text-indigo-400 text-base font-black">{formatNum(grandTotalVolume)} <span className="text-xs text-indigo-500">m³</span></td>
                                    <td className="px-4 py-5" />
                                    <td className="px-4 py-5 text-right font-mono text-slate-500 text-base font-black pr-8">{formatISK(grandTotalPrice)} <span className="text-xs text-slate-600">ISK</span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                )}
            </div>

            {/* MODAL 1: STRUCTURE & SYSTEM CONFIGURATION */}
            {isStructureModalOpen && (
                <div className="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fadeIn">
                    <div className="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full overflow-hidden shadow-2xl">
                        <div className="bg-slate-950 px-6 py-4 border-b border-slate-800 flex justify-between items-center">
                            <h3 className="font-bold text-slate-200 uppercase tracking-wider text-sm">Structure & Rig Settings</h3>
                            <button onClick={() => setIsStructureModalOpen(false)} className="text-slate-500 hover:text-slate-200 font-bold text-lg focus:outline-none">✕</button>
                        </div>
                        <div className="p-6 space-y-4">
                            <div>
                                <label className="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Structure Type</label>
                                <select value={structureType} onChange={(e) => setStructureType(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-sm font-semibold text-slate-200 focus:border-indigo-500 focus:outline-none">
                                    <option value="sotiyo">Sotiyo (Engineering Complex)</option>
                                    <option value="azbel">Azbel (Engineering Complex)</option>
                                    <option value="raitaru">Raitaru (Engineering Complex)</option>
                                    <option value="other">Standard Station / Pos</option>
                                </select>
                            </div>
                            <div>
                                <label className="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Installed Engineering Rig</label>
                                <select value={rigTier} onChange={(e) => setRigTier(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-sm font-semibold text-slate-200 focus:border-indigo-500 focus:outline-none">
                                    <option value="t2">Stand-alone T2 Material Efficiency Rig</option>
                                    <option value="t1">Stand-alone T1 Material Efficiency Rig</option>
                                    <option value="none">No Installed Rigs</option>
                                </select>
                            </div>
                            <div className="p-3 bg-slate-950 rounded-xl border border-slate-800/60 text-xs text-slate-500 italic">
                                Placeholder container. You can wire these selection variables directly into your server endpoint queries or local formula reductions later!
                            </div>
                        </div>
                        <div className="bg-slate-950 px-6 py-3.5 border-t border-slate-800 flex justify-end">
                            <button onClick={() => setIsStructureModalOpen(false)} className="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md">Apply Configuration</button>
                        </div>
                    </div>
                </div>
            )}

            {/* MODAL 2: REQUIRED SKILLS AND CLIPBOARD IMPORT */}
            {isSkillsModalOpen && (
                <div className="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fadeIn">
                    <div className="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl">
                        <div className="bg-slate-950 px-6 py-4 border-b border-slate-800 flex justify-between items-center">
                            <h3 className="font-bold text-slate-200 uppercase tracking-wider text-sm">Required Blueprint Skills</h3>
                            <button onClick={() => setIsSkillsModalOpen(false)} className="text-slate-500 hover:text-slate-200 font-bold text-lg focus:outline-none">✕</button>
                        </div>
                        <div className="p-6 max-h-[400px] overflow-y-auto space-y-3 divide-y divide-slate-800/40">
                            {skills.length === 0 ? (
                                <div className="text-center text-slate-500 text-sm py-4">No structural skill prerequisites found.</div>
                            ) : (
                                skills.map((skill, index) => (
                                    <div key={skill.typeID || index} className={`flex justify-between items-center text-sm pt-2.5 ${index === 0 ? 'border-none! pt-0' : ''}`}>
                                        <div className="flex items-center gap-2">
                                            <span className="text-[10px] bg-slate-950 font-mono text-indigo-400 px-1.5 py-0.5 rounded border border-slate-800">ID: {skill.typeID}</span>
                                            <span className="font-medium text-slate-300">{skill.name}</span>
                                        </div>
                                        <div className="flex gap-1">
                                            {[1, 2, 3, 4, 5].map((lvl) => (
                                                <span key={lvl} className={`w-3.5 h-3.5 rounded-xs border text-[9px] font-black flex items-center justify-center select-none ${lvl <= skill.level
                                                        ? 'bg-indigo-600 border-indigo-400 text-white'
                                                        : 'border-slate-800 bg-slate-950 text-slate-700'
                                                    }`}>
                                                    {lvl}
                                                </span>
                                            ))}
                                        </div>
                                    </div>
                                ))
                            )}
                        </div>
                        <div className="bg-slate-950 px-6 py-4 border-t border-slate-800 flex justify-between items-center">
                            <button onClick={() => setIsSkillsModalOpen(false)} className="text-slate-400 hover:text-slate-200 text-xs font-bold uppercase tracking-wider">Close</button>
                            {skills.length > 0 && (
                                <button
                                    type="button"
                                    onClick={handleCopySkillsToClipboard}
                                    className={`inline-flex items-center gap-1.5 px-4 py-2 text-xs font-black rounded-xl border uppercase tracking-wider shadow-md transition-all duration-150 ${skillsCopied ? 'bg-emerald-950/50 border-emerald-500 text-emerald-400' : 'bg-indigo-600 border-indigo-500 text-white hover:bg-indigo-500'
                                        }`}
                                >
                                    {skillsCopied ? '✓ Copied Queue!' : 'Copy Skills List'}
                                </button>
                            )}
                        </div>
                    </div>
                </div>
            )}

            {/* Bottom Actions Row Callouts */}
            {buildMode === 'direct' && materials.length > 0 && (
                <div className="flex justify-end pt-1">
                    <button
                        type="button"
                        onClick={handleCopyToClipboard}
                        disabled={loading}
                        className={`inline-flex items-center gap-2.5 px-5 py-3 text-xs font-black rounded-xl border uppercase tracking-wider shadow-xl transition-all duration-150 hover:-translate-y-0.5 active:translate-y-0 ${copied
                                ? 'bg-emerald-950/50 border-emerald-500 text-emerald-400'
                                : 'bg-slate-900 border-slate-800 hover:bg-slate-800 text-indigo-400 hover:text-indigo-300'
                            }`}
                    >
                        {copied ? '✓ Copied Adjusted Requirements!' : '📋 Copy Multibuy List'}
                    </button>
                </div>
            )}
        </div>
    );
}
import React, { useState, useEffect } from 'react';
import axios from 'axios';
import StructureSettingsModal from '@/Components/modals/StructureSettingsModal.js';
import RequiredSkillsModal from '@/Components/modals/RequiredSkillsModal.js';
import MaterialsTableDisplay from '@/Components/MaterialsTableDisplay.js';
import { getScaledQuantity } from '@/Components/helpers/industryUtils.js';

export default function MaterialRequirementsTable({ blueprintKey }) {
    const [buildMode, setBuildMode] = useState('tree');
    const [runs, setRuns] = useState(1);
    const [me, setMe] = useState(0);
    const [te, setTe] = useState(0);
    const [payload, setPayload] = useState(null);
    const [loading, setLoading] = useState(false);
    const [copied, setCopied] = useState(false);

    const [deconStates, setDeconStates] = useState({});
    const [collapsedNodes, setCollapsedNodes] = useState({});

    const [isStructureModalOpen, setIsStructureModalOpen] = useState(false);
    const [isSkillsModalOpen, setIsSkillsModalOpen] = useState(false);
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
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                setPayload(response.data);

                const materialsList = buildMode === 'tree'
                    ? response.data?.requirements?.materials
                    : response.data?.manufacturing?.materials;

                if (buildMode === 'tree' && materialsList) {
                    const initialDecon = {};
                    const extractIds = (items) => {
                        items.forEach(i => {
                            if (i.children && i.children.length > 0) {
                                initialDecon[i.typeID] = true;
                                extractIds(i.children);
                            }
                        });
                    };
                    extractIds(materialsList);
                    setDeconStates(initialDecon);
                }
            } catch (error) {
                console.error("Pipeline network breakdown parsing response:", error);
                setPayload(null);
            } finally { setLoading(false); }
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
    const industrySkillLevel = skills.find(s => s.name === 'Industry')?.level || 0;
    const advIndustrySkillLevel = skills.find(s => s.name === 'Advanced Industry')?.level || 0;

    const baseProductionTime = buildMode === 'direct'
        ? (payload?.manufacturing?.time || 0)
        : (payload?.requirements?.time || 0);

    const totalDurationSeconds = baseProductionTime * batchMultiplier * (1 - timeModifier) * (1 - industrySkillLevel * 0.04) * (1 - advIndustrySkillLevel * 0.03);

    const groupedMaterials = materials.reduce((acc, item) => {
        const group = item.group_name || "Production Components";
        if (!acc[group]) acc[group] = [];
        acc[group].push(item);
        return acc;
    }, {});

    const calculateTotals = (nodes) => {
        let volume = 0;
        let price = 0;

        nodes.forEach(node => {
            const isDeconstructed = !!deconStates[node.typeID];
            const scaledQty = getScaledQuantity(node.quantity, batchMultiplier, materialModifier);

            if (buildMode === 'tree' && node.children && node.children.length > 0 && isDeconstructed) {
                const subTotals = calculateTotals(node.children);
                volume += subTotals.volume;
                price += subTotals.price;
            } else {
                volume += (node.unit_volume || 0) * scaledQty;
                price += (node.estimated_price || 0) * scaledQty;
            }
        });
        return { volume, price };
    };

    const { volume: grandTotalVolume, price: grandTotalPrice } = calculateTotals(materials);

    const toggleDecon = (typeID) => {
        setDeconStates(prev => ({ ...prev, [typeID]: !prev[typeID] }));
    };

    const toggleCollapse = (typeID) => {
        setCollapsedNodes(prev => ({ ...prev, [typeID]: !prev[typeID] }));
    };

    const handleCopyToClipboard = () => {
        if (materials.length === 0) return;
        const listText = materials.map(item => `${item.name} ${getScaledQuantity(item.quantity, batchMultiplier, materialModifier)}`).join('\n');
        navigator.clipboard.writeText(listText).then(() => { setCopied(true); setTimeout(() => setCopied(false), 2000); });
    };

    return (
        <div className="space-y-4 w-full text-slate-200 relative">
            <div className="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
                <div className="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                    <div className="inline-flex bg-slate-900/90 p-1 rounded-xl border border-slate-800 shadow-lg shrink-0">
                        {['direct', 'tree'].map((mode) => (
                            <button key={mode} type="button" onClick={() => setBuildMode(mode)} className={`px-4 py-2 text-xs font-bold tracking-wide uppercase rounded-lg transition-all duration-200 ${buildMode === mode ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200'}`}>{mode === 'direct' ? 'Direct Buy' : 'Full Tree'}</button>
                        ))}
                    </div>

                    <div className="flex gap-2">
                        <button type="button" onClick={() => setIsStructureModalOpen(true)} className="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 px-4 py-2 text-xs font-bold rounded-xl uppercase tracking-wider shadow-lg h-[42px] flex items-center">Structure Settings</button>
                        <button type="button" onClick={() => setIsSkillsModalOpen(true)} className="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-300 px-4 py-2 text-xs font-bold rounded-xl uppercase tracking-wider shadow-lg h-[42px] flex items-center">Required Skills ({skills.length})</button>
                    </div>

                    <div className="flex flex-wrap items-center gap-3 bg-slate-900/90 rounded-xl border border-slate-800 p-1 shadow-lg">
                        <div className="flex items-center px-2 py-1">
                            <label htmlFor="bpo-runs" className="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">Runs:</label>
                            <input id="bpo-runs" type="number" min="1" value={runs} onChange={(e) => setRuns(e.target.value)} className="w-14 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-md text-center font-mono font-bold text-xs text-indigo-400 p-1 focus:outline-none [appearance:textfield]" />
                        </div>
                        <div className="w-[1px] h-5 bg-slate-800" />
                        <div className="flex items-center px-2 py-1">
                            <label htmlFor="bpo-me" className="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">ME %:</label>
                            <input id="bpo-me" type="number" min="0" max="10" value={me} onChange={(e) => setMe(e.target.value)} className="w-12 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-md text-center font-mono font-bold text-xs text-emerald-400 p-1 focus:outline-none [appearance:textfield]" />
                        </div>
                        <div className="w-[1px] h-5 bg-slate-800" />
                        <div className="flex items-center px-2 py-1">
                            <label htmlFor="bpo-te" className="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">TE %:</label>
                            <input id="bpo-te" type="number" min="0" max="10" value={te} onChange={(e) => setTe(e.target.value)} className="w-12 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-md text-center font-mono font-bold text-xs text-amber-400 p-1 focus:outline-none [appearance:textfield]" />
                        </div>
                    </div>
                </div>

                {materials.length > 0 && (
                    <button type="button" onClick={handleCopyToClipboard} disabled={loading} className={`inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-xl border uppercase tracking-wider shadow-lg h-[42px] shrink-0 w-full xl:w-auto justify-center ${copied ? 'bg-emerald-950/40 border-emerald-500 text-emerald-400' : 'bg-slate-900 border-slate-800 hover:bg-slate-800 text-indigo-400'}`}>
                        {copied ? '✓ Copied Multibuy' : '📋 Copy Multibuy'}
                    </button>
                )}
            </div>

            <MaterialsTableDisplay
                buildMode={buildMode} loading={loading} materials={materials} groupedMaterials={groupedMaterials}
                deconStates={deconStates} onToggleDecon={toggleDecon} collapsedNodes={collapsedNodes} onToggleCollapse={toggleCollapse}
                batchMultiplier={batchMultiplier} materialModifier={materialModifier} baseProductionTime={baseProductionTime}
                totalDurationSeconds={totalDurationSeconds} payloadName={payload?.name} grandTotalVolume={grandTotalVolume} grandTotalPrice={grandTotalPrice}
                onCopyClipboard={handleCopyToClipboard}
            />

            <StructureSettingsModal isOpen={isStructureModalOpen} onClose={() => setIsStructureModalOpen(false)} structureType={structureType} setStructureType={setStructureType} rigTier={rigTier} setRigTier={setRigTier} />
            <RequiredSkillsModal isOpen={isSkillsModalOpen} onClose={() => setIsSkillsModalOpen(false)} skills={skills} />
        </div>
    );
}
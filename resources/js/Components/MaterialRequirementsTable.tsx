import React, { useState, useEffect, useMemo } from 'react';
import axios from 'axios';
import StructureSettingsModal from '@/Components/modals/StructureSettingsModal.js';
import RequiredSkillsModal from '@/Components/modals/RequiredSkillsModal.js';
import BlueprintShoppingListModal from '@/Components/modals/BlueprintShoppingListModal.js';
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

    // Dynamic Tree UI Core States - Now tracking via path strings to isolate identical item IDs
    const [deconStates, setDeconStates] = useState({});
    const [collapsedNodes, setCollapsedNodes] = useState({});
    const [searchTerm, setSearchTerm] = useState('');

    const [isStructureModalOpen, setIsStructureModalOpen] = useState(false);
    const [isSkillsModalOpen, setIsSkillsModalOpen] = useState(false);
    const [isBpModalOpen, setIsBpModalOpen] = useState(false);

    // Assembly Engineering Variables
    const [structureType, setStructureType] = useState('sotiyo');
    const [rigTier, setRigTier] = useState('t2');
    const [securityZone, setSecurityZone] = useState('nullsec');
    const [systemCostIndex, setSystemCostIndex] = useState(1.5);

    // Helper to inject contextual unique paths directly into the tree structure nodes
    const buildRecursiveTree = (materialsList, formulas, parentPath = "root") => {
        if (!materialsList) return [];
        return materialsList.map((material, index) => {
            const currentPath = `${parentPath}->${material.typeID}_${index}`;
            const formulaId = material.formula_id;
            const subFormula = formulaId && formulas ? formulas[formulaId] : null;
            let children = [];
            if (subFormula && subFormula.materials) {
                children = buildRecursiveTree(subFormula.materials, formulas, currentPath);
            }
            return {
                ...material,
                path: currentPath,
                children: children
            };
        });
    };

    useEffect(() => {
        if (!blueprintKey) return;
        const loadData = async () => {
            setLoading(true);
            const routeName = buildMode === 'tree' ? 'industry.fullTree' : 'industry.directBuy';
            try {
                const targetUrl = typeof route !== 'undefined'
                    ? route(routeName, { _key: blueprintKey })
                    : `/api/industry/${buildMode === 'tree' ? 'tree' : 'direct'}?_key=${blueprintKey}`;

                const response = await axios.get(targetUrl, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });

                setPayload(response.data);

                if (buildMode === 'tree' && response.data) {
                    const rawMaterials = response.data?.requirements?.materials || [];
                    const formulas = response.data?.formulas || {};
                    const fullTreeData = buildRecursiveTree(rawMaterials, formulas);

                    const initialDecon = {};
                    const initialCollapse = {};

                    const extractPaths = (items) => {
                        items.forEach(i => {
                            if (i.children && i.children.length > 0) {
                                initialDecon[i.path] = true;
                                initialCollapse[i.path] = true;
                                extractPaths(i.children);
                            }
                        });
                    };
                    extractPaths(fullTreeData);
                    setDeconStates(initialDecon);
                    setCollapsedNodes(initialCollapse);
                }
            } catch (error) {
                console.error("Pipeline breakdown fetching structural information:", error);
                setPayload(null);
            } finally { setLoading(false); }
        };
        loadData();
    }, [blueprintKey, buildMode]);

    // ME/TE Calculation Core Modifiers
    let structureMeModifier = 1.0;
    let structureTeModifier = 1.0;

    if (structureType === 'sotiyo') {
        structureMeModifier *= 0.99;
        structureTeModifier *= 0.70;
    } else if (structureType === 'azbel') {
        structureMeModifier *= 0.99;
        structureTeModifier *= 0.75;
    } else if (structureType === 'raitaru') {
        structureMeModifier *= 0.99;
        structureTeModifier *= 0.85;
    }

    const securityRigMultiplier = securityZone === 'highsec' ? 0.5 : 1.0;

    if (rigTier === 't2') {
        structureMeModifier *= (1 - (0.024 * securityRigMultiplier));
    } else if (rigTier === 't1') {
        structureMeModifier *= (1 - (0.020 * securityRigMultiplier));
    }

    const batchMultiplier = Math.max(1, parseInt(runs) || 1);
    const materialModifier = (Math.min(10, Math.max(0, parseInt(me) || 0)) / 100);
    const timeModifier = Math.min(10, Math.max(0, parseInt(te) || 0)) / 100;

    let materials = [];
    if (buildMode === 'direct') {
        materials = payload?.manufacturing?.materials || [];
    } else {
        const rawMaterials = payload?.requirements?.materials || [];
        const formulas = payload?.formulas || {};
        materials = buildRecursiveTree(rawMaterials, formulas);
    }

    const skills = payload?.skills || [];
    const industrySkillLevel = skills.find(s => s.name === 'Industry')?.level || 0;
    const advIndustrySkillLevel = skills.find(s => s.name === 'Advanced Industry')?.level || 0;

    const baseProductionTime = buildMode === 'direct' ? (payload?.manufacturing?.time || 0) : (payload?.requirements?.time || 0);
    const totalDurationSeconds = baseProductionTime * batchMultiplier * (1 - timeModifier) * structureTeModifier * (1 - industrySkillLevel * 0.04) * (1 - advIndustrySkillLevel * 0.03);

    const calculateTotals = (nodes) => {
        let volume = 0;
        let price = 0;

        nodes.forEach(node => {
            const isDeconstructed = !!deconStates[node.path];
            const displayQty = Math.ceil(getScaledQuantity(node.quantity, batchMultiplier, materialModifier) * structureMeModifier);

            if (buildMode === 'tree' && node.children && node.children.length > 0 && isDeconstructed) {
                const subTotals = calculateTotals(node.children);
                volume += subTotals.volume;
                price += subTotals.price;
                volume += (node.packaged_volume || node.unit_volume || 0) * displayQty;
            } else {
                volume += (node.packaged_volume || node.unit_volume || 0) * displayQty;
                price += (node.estimated_price || 0) * displayQty;
            }
        });
        return { volume, price };
    };

    const { volume: grandTotalVolume, price: grandTotalPrice } = calculateTotals(materials);

    const handleBulkDeconstruct = (setDeconTo: boolean) => {
        const updatedDecon = {};
        const applyBulk = (nodes) => {
            nodes.forEach(node => {
                if (node.children && node.children.length > 0) {
                    updatedDecon[node.path] = setDeconTo;
                    applyBulk(node.children);
                }
            });
        };
        applyBulk(materials);
        setDeconStates(updatedDecon);
    };

    const handleBulkCollapse = (setCollapseTo: boolean) => {
        const updatedCollapse = {};
        const applyBulk = (nodes) => {
            nodes.forEach(node => {
                if (node.children && node.children.length > 0) {
                    updatedCollapse[node.path] = setCollapseTo;
                    applyBulk(node.children);
                }
            });
        };
        applyBulk(materials);
        setCollapsedNodes(updatedCollapse);
    };

    // Isolated tracking via specific instance pathing string signatures
    const toggleDecon = (nodePath) => {
        setDeconStates(prev => {
            const nextState = { ...prev, [nodePath]: !prev[nodePath] };

            if (!nextState[nodePath]) {
                const clearNestedPaths = (nodes) => {
                    for (const node of nodes) {
                        if (node.path === nodePath) {
                            const clearNested = (items) => {
                                items.forEach(item => {
                                    nextState[item.path] = false;
                                    if (item.children) clearNested(item.children);
                                });
                            };
                            if (node.children) clearNested(node.children);
                            break;
                        }
                        if (node.children) clearNestedPaths(node.children);
                    }
                };
                clearNestedPaths(materials);
            }
            return nextState;
        });
    };

    const toggleCollapse = (nodePath) => {
        setCollapsedNodes(prev => ({ ...prev, [nodePath]: !prev[nodePath] }));
    };

    const blueprintShoppingList = useMemo(() => {
        if (!payload || !payload.name) return [];

        const blueprintMap = {};
        blueprintMap[blueprintKey || payload.blueprintTypeID || 'parent'] = {
            name: payload.name.endsWith('Blueprint') ? payload.name : `${payload.name} Blueprint`,
            totalRuns: batchMultiplier,
            maxProductionLimit: payload.maxProductionLimit || 1,
            activityType: 'manufacturing'
        };

        const traverse = (nodes, currentRuns) => {
            nodes.forEach(node => {
                const isDeconstructed = !!deconStates[node.path];
                if (buildMode === 'tree' && node.children && node.children.length > 0 && isDeconstructed) {
                    const materialsNeeded = Math.ceil(getScaledQuantity(node.quantity, currentRuns, materialModifier) * structureMeModifier);
                    const bpTypeID = node.typeID;
                    const groupNameLower = node.group_name?.toLowerCase() || '';
                    const nameLower = node.name?.toLowerCase() || '';

                    const isReaction = groupNameLower.includes('reaction') || nameLower.includes('formula') || ['Biochemical Reactions', 'Composite Reactions', 'Hybrid Reactions'].includes(node.group_name);

                    let bpName = node.name;
                    if (isReaction) {
                        if (!bpName.toLowerCase().endsWith('formula')) bpName = bpName.replace(/blueprint/i, '').trim() + ' Reaction Formula';
                    } else {
                        if (!bpName.toLowerCase().endsWith('blueprint')) bpName = bpName.replace(/formula/i, '').trim() + ' Blueprint';
                    }

                    if (!blueprintMap[bpTypeID]) {
                        blueprintMap[bpTypeID] = {
                            name: bpName,
                            totalRuns: 0,
                            maxProductionLimit: node.maxProductionLimit || (isReaction ? 10000 : 10),
                            activityType: isReaction ? 'reaction' : 'manufacturing'
                        };
                    }
                    blueprintMap[bpTypeID].totalRuns += materialsNeeded;
                    traverse(node.children, materialsNeeded);
                }
            });
        };

        if (buildMode === 'tree' && materials.length > 0) {
            traverse(materials, batchMultiplier);
        }

        return Object.values(blueprintMap).map((bp: any) => {
            const copiesNeeded = Math.ceil(bp.totalRuns / bp.maxProductionLimit);
            return {
                name: bp.name,
                totalRuns: bp.totalRuns,
                runsPerCopy: copiesNeeded > 1 ? bp.maxProductionLimit : bp.totalRuns,
                copiesNeeded: copiesNeeded,
                activityType: bp.activityType
            };
        });
    }, [payload, buildMode, materials, deconStates, batchMultiplier, materialModifier, structureMeModifier, blueprintKey]);

    const handleCopyToClipboard = () => {
        if (materials.length === 0) return;
        let listText = "";
        if (buildMode === 'tree') {
            const flattenMaterials = (nodes) => {
                let lines = [];
                nodes.forEach(node => {
                    const isDeconstructed = !!deconStates[node.path];
                    const scaledQty = Math.ceil(getScaledQuantity(node.quantity, batchMultiplier, materialModifier) * structureMeModifier);
                    if (node.children && node.children.length > 0 && isDeconstructed) {
                        lines = [...lines, ...flattenMaterials(node.children)];
                    } else {
                        lines.push(`${node.name} ${scaledQty}`);
                    }
                });
                return lines;
            };
            listText = flattenMaterials(materials).join('\n');
        } else {
            listText = materials.map(item => {
                const scaledQty = Math.ceil(getScaledQuantity(item.quantity, batchMultiplier, materialModifier) * structureMeModifier);
                return `${item.name} ${scaledQty}`;
            }).join('\n');
        }
        navigator.clipboard.writeText(listText).then(() => { setCopied(true); setTimeout(() => setCopied(false), 2000); });
    };

    const handleCopySubTreeToClipboard = (targetNode) => {
        if (!targetNode) return;
        const parseLines = (node, currentMultiplier) => {
            let lines = [];
            const isDeconstructed = !!deconStates[node.path];
            if (node.children && node.children.length > 0 && isDeconstructed) {
                const requiredParentRuns = Math.ceil(getScaledQuantity(node.quantity, currentMultiplier, materialModifier) * structureMeModifier);
                node.children.forEach(child => {
                    lines = [...lines, ...parseLines(child, requiredParentRuns)];
                });
            } else {
                const scaledQty = Math.ceil(getScaledQuantity(node.quantity, currentMultiplier, materialModifier) * structureMeModifier);
                lines.push(`${node.name} ${scaledQty}`);
            }
            return lines;
        };

        let outputLines = [];
        if (targetNode.children && targetNode.children.length > 0 && deconStates[targetNode.path]) {
            const scaledParentRuns = Math.ceil(getScaledQuantity(targetNode.quantity, batchMultiplier, materialModifier) * structureMeModifier);
            targetNode.children.forEach(child => {
                outputLines = [...outputLines, ...parseLines(child, scaledParentRuns)];
            });
        } else {
            const targetQty = Math.ceil(getScaledQuantity(targetNode.quantity, batchMultiplier, materialModifier) * structureMeModifier);
            outputLines.push(`${targetNode.name} ${targetQty}`);
        }
        navigator.clipboard.writeText(outputLines.join('\n'));
    };

    const outputProduct = payload?.requirements?.products?.[0] || payload?.manufacturing?.products?.[0] || null;

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
                        <button type="button" onClick={() => setIsBpModalOpen(true)} className="bg-indigo-950/40 border border-indigo-800 hover:bg-indigo-900/40 text-indigo-400 px-4 py-2 text-xs font-bold rounded-xl uppercase tracking-wider shadow-lg h-[42px] flex items-center">Blueprint Explorer ({blueprintShoppingList.length})</button>
                    </div>

                    <div className="flex flex-wrap items-center gap-3 bg-slate-900/90 rounded-xl border border-slate-800 p-1 shadow-lg">
                        <div className="flex items-center px-2 py-1">
                            <label htmlFor="bpo-runs" className="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">Runs:</label>
                            <input id="bpo-runs" type="number" min="1" value={runs} onChange={(e) => setRuns(parseInt(e.target.value) || 1)} className="w-14 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-md text-center font-mono font-bold text-xs text-indigo-400 p-1 focus:outline-none [appearance:textfield]" />
                        </div>
                        <div className="w-[1px] h-5 bg-slate-800" />
                        <div className="flex items-center px-2 py-1">
                            <label htmlFor="bpo-me" className="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">ME %:</label>
                            <input id="bpo-me" type="number" min="0" max="10" value={me} onChange={(e) => setMe(parseInt(e.target.value) || 0)} className="w-12 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-md text-center font-mono font-bold text-xs text-emerald-400 p-1 focus:outline-none [appearance:textfield]" />
                        </div>
                        <div className="w-[1px] h-5 bg-slate-800" />
                        <div className="flex items-center px-2 py-1">
                            <label htmlFor="bpo-te" className="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">TE %:</label>
                            <input id="bpo-te" type="number" min="0" max="10" value={te} onChange={(e) => setTe(parseInt(e.target.value) || 0)} className="w-12 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-md text-center font-mono font-bold text-xs text-amber-400 p-1 focus:outline-none [appearance:textfield]" />
                        </div>
                    </div>
                </div>
            </div>

            <MaterialsTableDisplay
                buildMode={buildMode} loading={loading} materials={materials}
                deconStates={deconStates} onToggleDecon={toggleDecon} collapsedNodes={collapsedNodes} onToggleCollapse={toggleCollapse}
                batchMultiplier={batchMultiplier} materialModifier={materialModifier} structureMeModifier={structureMeModifier} baseProductionTime={baseProductionTime}
                totalDurationSeconds={totalDurationSeconds} payloadName={payload?.name} grandTotalVolume={grandTotalVolume} grandTotalPrice={grandTotalPrice}
                onCopyClipboard={handleCopyToClipboard} outputProduct={outputProduct} onCopySubTree={handleCopySubTreeToClipboard}
                searchTerm={searchTerm} setSearchTerm={setSearchTerm}
                onBulkDeconstruct={handleBulkDeconstruct} onBulkCollapse={handleBulkCollapse}
            />

            <StructureSettingsModal
                isOpen={isStructureModalOpen} onClose={() => setIsStructureModalOpen(false)}
                structureType={structureType} setStructureType={setStructureType}
                rigTier={rigTier} setRigTier={setRigTier} securityZone={securityZone} setSecurityZone={setSecurityZone}
                systemCostIndex={systemCostIndex} setSystemCostIndex={setSystemCostIndex}
            />

            <RequiredSkillsModal isOpen={isSkillsModalOpen} onClose={() => setIsSkillsModalOpen(false)} skills={skills} />
            <BlueprintShoppingListModal isOpen={isBpModalOpen} onClose={() => setIsBpModalOpen(false)} blueprints={blueprintShoppingList} />
        </div>
    );
}

// todo:

// in the thanatos blueprint search for fuel block, then uncheck it and it doesnt work
// the children of the looked upon node are not shown.
// check ferox blueprint, the structure + me/te calculation is off see: https://imgur.com/a/qxflzdu
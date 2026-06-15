import React from 'react';

export default function StructureSettingsModal({
    isOpen, onClose,
    structureType, setStructureType,
    rigTier, setRigTier,
    securityZone, setSecurityZone,
    systemCostIndex, setSystemCostIndex
}) {
    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div className="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full overflow-hidden shadow-2xl">
                <div className="bg-slate-950 px-6 py-4 border-b border-slate-800 flex justify-between items-center">
                    <h3 className="font-bold text-slate-200 uppercase tracking-wider text-sm">Structure Settings</h3>
                    <button type="button" onClick={onClose} className="text-slate-500 hover:text-slate-200 font-bold">✕</button>
                </div>
                <div className="p-6 space-y-4">
                    {/* Structure Type */}
                    <div>
                        <label className="block text-xs font-bold uppercase text-slate-400 mb-2">Structure Type</label>
                        <select value={structureType} onChange={(e) => setStructureType(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-sm text-slate-200 focus:outline-none">
                            <option value="sotiyo">Sotiyo (Engineering Complex)</option>
                            <option value="azbel">Azbel (Engineering Complex)</option>
                            <option value="raitaru">Raitaru (Engineering Complex)</option>
                            <option value="other">Standard Station / Outpost</option>
                        </select>
                    </div>

                    {/* Regional Space Security Zone */}
                    <div>
                        <label className="block text-xs font-bold uppercase text-slate-400 mb-2">Location Security Space</label>
                        <select value={securityZone} onChange={(e) => setSecurityZone(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-sm text-slate-200 focus:outline-none">
                            <option value="nullsec">Null-Sec / Wormhole Space (1.0x Rig Bonus)</option>
                            <option value="lowsec">Low-Sec Space (1.0x Rig Bonus)</option>
                            <option value="highsec">High-Sec Space (0.5x Rig Penalty)</option>
                        </select>
                    </div>

                    {/* Installed Rig */}
                    <div>
                        <label className="block text-xs font-bold uppercase text-slate-400 mb-2">Installed Rig</label>
                        <select value={rigTier} onChange={(e) => setRigTier(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-sm text-slate-200 focus:outline-none">
                            <option value="t2">T2 Material Efficiency Rig</option>
                            <option value="t1">T1 Material Efficiency Rig</option>
                            <option value="none">No Rig / Unrigged Structure</option>
                        </select>
                    </div>

                    {/* Solar System Activity Index Fee */}
                    <div>
                        <label htmlFor="sci-input" className="block text-xs font-bold uppercase text-slate-400 mb-2">System Cost Index (SCI %)</label>
                        <div className="relative">
                            <input
                                id="sci-input"
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                value={systemCostIndex}
                                onChange={(e) => setSystemCostIndex(parseFloat(e.target.value) || 0)}
                                className="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl p-2.5 text-sm text-slate-200 font-mono focus:outline-none"
                            />
                            <span className="absolute right-4 top-2.5 text-xs font-bold text-slate-500 font-mono">%</span>
                        </div>
                    </div>
                </div>
                <div className="bg-slate-950 px-6 py-3.5 border-t border-slate-800 flex justify-end">
                    <button type="button" onClick={onClose} className="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider">Apply Settings</button>
                </div>
            </div>
        </div>
    );
}
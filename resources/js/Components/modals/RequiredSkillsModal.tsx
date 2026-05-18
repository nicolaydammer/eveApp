import React, { useState } from 'react';

export default function RequiredSkillsModal({ isOpen, onClose, skills }) {
    const [skillsCopied, setSkillsCopied] = useState(false);
    if (!isOpen) return null;

    const handleCopy = () => {
        const text = skills.map(s => `${s.name} Level ${s.level}`).join('\n');
        navigator.clipboard.writeText(text).then(() => {
            setSkillsCopied(true);
            setTimeout(() => setSkillsCopied(false), 2000);
        });
    };

    return (
        <div className="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div className="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl">
                <div className="bg-slate-950 px-6 py-4 border-b border-slate-800 flex justify-between items-center">
                    <h3 className="font-bold text-slate-200 uppercase tracking-wider text-sm">Required Blueprint Skills</h3>
                    <button onClick={onClose} className="text-slate-500 hover:text-slate-200 font-bold">✕</button>
                </div>
                <div className="p-6 max-h-[350px] overflow-y-auto space-y-3 divide-y divide-slate-800/40">
                    {skills.length === 0 ? (
                        <div className="text-center text-slate-500 text-sm py-4">No skill prerequisites found.</div>
                    ) : (
                        skills.map((skill, i) => (
                            <div key={i} className="flex justify-between items-center text-sm pt-2.5">
                                <span className="font-medium text-slate-300">{skill.name}</span>
                                <div className="flex gap-1">
                                    {[1, 2, 3, 4, 5].map((lvl) => (
                                        <span key={lvl} className={`w-3.5 h-3.5 rounded-xs border text-[9px] font-black flex items-center justify-center ${lvl <= skill.level ? 'bg-indigo-600 text-white' : 'border-slate-800 bg-slate-950 text-slate-700'}`}>{lvl}</span>
                                    ))}
                                </div>
                            </div>
                        ))
                    )}
                </div>
                <div className="bg-slate-950 px-6 py-4 border-t border-slate-800 flex justify-between items-center">
                    <button onClick={onClose} className="text-slate-400 text-xs font-bold uppercase">Close</button>
                    <button onClick={handleCopy} className="bg-indigo-600 text-white px-4 py-2 text-xs font-bold rounded-xl uppercase">{skillsCopied ? '✓ Copied' : 'Copy Queue'}</button>
                </div>
            </div>
        </div>
    );
}
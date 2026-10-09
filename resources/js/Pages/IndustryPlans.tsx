import AppLayout from '@/Layouts/AppLayout.js';
import IndustryCalculator from '@/industry/pages/IndustryCalculator.js';
import { Pencil, Plus, Trash2 } from 'lucide-react';
import { useEffect, useState } from 'react';
import axios from '@/lib/axios.js';
import { route } from "ziggy-js";

type IndustryPlan = {
    id: number;
    name: string;
};

type UpdatePlanData = {
    name: string;
    plan: Record<string, unknown>;
};

async function listPlans(): Promise<IndustryPlan[]> {
    const response = await axios.get(
        route('industry.plans.list')
    );

    return response.data;
}

async function getPlan(id: number): Promise<IndustryPlan> {
    const response = await axios.get(
        route('industry.plans.get', { id: id })
    );

    return response.data;
}

async function createPlan(name: string): Promise<number> {
    const response = await axios.post(
        route('industry.plans.create'),
        {
            name: name
        }
    );

    return response.data;
}

async function updatePlan(
    id: number,
    data: UpdatePlanData
): Promise<void> {
    const response = await axios.patch(
        route('industry.plans.update', { id: id }),
        data
    );

    return response.data;
}

async function deletePlan(id: number): Promise<void> {
    const response = await axios.delete(
        route('industry.plans.delete', { id: id })
    );

    return response.data;
}

export default function Industry() {
    const [plans, setPlans] = useState<IndustryPlan[]>([]);
    const [planId, setPlanId] = useState<number | null>(null);

    const [loading, setLoading] = useState(true);

    const [showNewPlanModal, setShowNewPlanModal] = useState(false);
    const [newPlanName, setNewPlanName] = useState('');
    const [saving, setSaving] = useState(false);

    const [editingPlanId, setEditingPlanId] = useState<number | null>(null);
    const [editingPlanName, setEditingPlanName] = useState('');
    const [renaming, setRenaming] = useState(false);

    useEffect(() => {
        listPlans()
            .then(setPlans)
            .finally(() => setLoading(false));
    }, []);

    async function handleCreatePlan() {
        const name = newPlanName.trim();

        if (name === '') {
            return;
        }

        setSaving(true);

        try {
            const id = await createPlan(name);

            setShowNewPlanModal(false);
            setNewPlanName('');

            setPlanId(id);
        } finally {
            setSaving(false);
        }
    }

    function handleRenameStart(plan: IndustryPlan) {
        setEditingPlanId(plan.id);
        setEditingPlanName(plan.name);
    }

    function handleRenameCancel() {
        setEditingPlanId(null);
        setEditingPlanName('');
    }

    async function handleRenameSave(id: number) {
        const name = editingPlanName.trim();

        if (name === '') {
            return;
        }

        setRenaming(true);

        try {
            await updatePlan(id, {
                name,
                plan: {},
            });

            setPlans((currentPlans) =>
                currentPlans.map((plan) =>
                    plan.id === id
                        ? {
                            ...plan,
                            name,
                        }
                        : plan
                )
            );

            handleRenameCancel();
        } finally {
            setRenaming(false);
        }
    }

    async function handleDelete(id: number) {
        await deletePlan(id);

        setPlans((currentPlans) =>
            currentPlans.filter((plan) => plan.id !== id)
        );
    }

    /*
     * A selected plan means we're now inside the actual
     * industry workspace.
     */
    if (planId !== null) {
        return (
            <AppLayout>
                <IndustryCalculator
                    planId={planId}
                    onBack={() => setPlanId(null)}
                />
            </AppLayout>
        );
    }

    return (
        <AppLayout>
            <div className="max-w-8xl mx-auto">
                {/* Page header */}
                <div className="flex items-center justify-between mb-6">
                    <h1 className="text-2xl font-bold">
                        Industry Plans
                    </h1>
                </div>

                {/* Saved plans */}
                <div className="border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden">
                    <div className="flex items-center justify-between px-4 py-4 border-b border-zinc-200 dark:border-zinc-800">
                        <div>
                            <h2 className="font-semibold">
                                Saved plans
                            </h2>

                            <p className="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                View and manage your industry production plans.
                            </p>
                        </div>

                        <button
                            type="button"
                            onClick={() => setShowNewPlanModal(true)}
                            className="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
                                bg-green-600 text-white
                                hover:bg-green-700
                                transition"
                        >
                            <Plus size={16} />
                            New plan
                        </button>
                    </div>

                    <div>
                        {loading ? (
                            <div className="px-4 py-6 text-sm text-zinc-500 dark:text-zinc-400">
                                Loading plans...
                            </div>
                        ) : plans.length === 0 ? (
                            <div className="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                No plans yet.
                            </div>
                        ) : (
                            plans.map((plan) => {
                                const editing = editingPlanId === plan.id;

                                return (
                                    <div
                                        key={plan.id}
                                        onClick={() => {
                                            if (!editing) {
                                                setPlanId(plan.id);
                                            }
                                        }}
                                        className="group flex items-center justify-between px-4 py-4
                                            border-b last:border-b-0
                                            border-zinc-200 dark:border-zinc-800
                                            hover:bg-zinc-100 dark:hover:bg-zinc-900
                                            cursor-pointer
                                            transition"
                                    >
                                        {/* Plan */}
                                        {editing ? (
                                            <input
                                                type="text"
                                                value={editingPlanName}
                                                onChange={(event) =>
                                                    setEditingPlanName(
                                                        event.target.value
                                                    )
                                                }
                                                onClick={(event) =>
                                                    event.stopPropagation()
                                                }
                                                onKeyDown={(event) => {
                                                    if (event.key === 'Enter') {
                                                        event.preventDefault();
                                                        handleRenameSave(plan.id);
                                                    }

                                                    if (event.key === 'Escape') {
                                                        event.preventDefault();
                                                        handleRenameCancel();
                                                    }
                                                }}
                                                autoFocus
                                                disabled={renaming}
                                                className="flex-1 px-3 py-2 rounded-lg text-sm
                                                    border border-zinc-200 dark:border-zinc-700
                                                    bg-white dark:bg-zinc-950
                                                    focus:outline-none focus:ring-2 focus:ring-green-500"
                                            />
                                        ) : (
                                            <div className="flex-1 text-sm font-medium">
                                                {plan.name}
                                            </div>
                                        )}

                                        {/* Actions */}
                                        <div className="flex items-center gap-2 ml-4">
                                            {editing ? (
                                                <>
                                                    <button
                                                        type="button"
                                                        onClick={(event) => {
                                                            event.stopPropagation();
                                                            handleRenameSave(plan.id);
                                                        }}
                                                        disabled={
                                                            renaming ||
                                                            editingPlanName.trim() === ''
                                                        }
                                                        className="px-3 py-2 rounded-lg text-sm font-medium
                                                            bg-green-600 text-white
                                                            hover:bg-green-700
                                                            disabled:opacity-50
                                                            disabled:cursor-not-allowed
                                                            transition"
                                                    >
                                                        {renaming
                                                            ? 'Saving...'
                                                            : 'Save'}
                                                    </button>

                                                    <button
                                                        type="button"
                                                        onClick={(event) => {
                                                            event.stopPropagation();
                                                            handleRenameCancel();
                                                        }}
                                                        disabled={renaming}
                                                        className="px-3 py-2 rounded-lg text-sm
                                                            border border-zinc-200 dark:border-zinc-700
                                                            hover:bg-zinc-200 dark:hover:bg-zinc-800
                                                            disabled:opacity-50
                                                            transition"
                                                    >
                                                        Cancel
                                                    </button>
                                                </>
                                            ) : (
                                                <>
                                                    <button
                                                        type="button"
                                                        onClick={(event) => {
                                                            event.stopPropagation();
                                                            handleRenameStart(plan);
                                                        }}
                                                        className="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                                                            border border-zinc-200 dark:border-zinc-700
                                                            hover:bg-zinc-200 dark:hover:bg-zinc-800
                                                            transition"
                                                    >
                                                        <Pencil size={14} />
                                                        Rename
                                                    </button>

                                                    <button
                                                        type="button"
                                                        onClick={(event) => {
                                                            event.stopPropagation();
                                                            handleDelete(plan.id);
                                                        }}
                                                        className="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                                                            border border-red-300 dark:border-red-900
                                                            text-red-600 dark:text-red-400
                                                            hover:bg-red-50 dark:hover:bg-red-950/30
                                                            transition"
                                                    >
                                                        <Trash2 size={14} />
                                                        Delete
                                                    </button>
                                                </>
                                            )}
                                        </div>
                                    </div>
                                );
                            })
                        )}
                    </div>
                </div>
            </div>

            {/* New plan modal */}
            {showNewPlanModal && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                    onClick={() => {
                        if (!saving) {
                            setShowNewPlanModal(false);
                        }
                    }}
                >
                    <div
                        className="w-full max-w-md rounded-lg
                            bg-white dark:bg-zinc-900
                            border border-zinc-200 dark:border-zinc-800
                            shadow-xl p-6"
                        onClick={(event) => event.stopPropagation()}
                    >
                        <h2 className="text-lg font-semibold">
                            New industry plan
                        </h2>

                        <p className="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                            Give your new plan a name.
                        </p>

                        <input
                            type="text"
                            value={newPlanName}
                            onChange={(event) =>
                                setNewPlanName(event.target.value)
                            }
                            onKeyDown={(event) => {
                                if (event.key === 'Enter') {
                                    event.preventDefault();
                                    handleCreatePlan();
                                }

                                if (event.key === 'Escape') {
                                    event.preventDefault();

                                    if (!saving) {
                                        setShowNewPlanModal(false);
                                        setNewPlanName('');
                                    }
                                }
                            }}
                            autoFocus
                            placeholder="Plan name"
                            className="w-full mt-4 px-3 py-2 rounded-lg
                                border border-zinc-200 dark:border-zinc-700
                                bg-white dark:bg-zinc-950
                                text-sm
                                focus:outline-none focus:ring-2 focus:ring-green-500"
                        />

                        <div className="flex justify-end gap-2 mt-6">
                            <button
                                type="button"
                                onClick={() => {
                                    setShowNewPlanModal(false);
                                    setNewPlanName('');
                                }}
                                disabled={saving}
                                className="px-4 py-2 rounded-lg text-sm
                                    border border-zinc-200 dark:border-zinc-700
                                    hover:bg-zinc-100 dark:hover:bg-zinc-800
                                    disabled:opacity-50
                                    transition"
                            >
                                Cancel
                            </button>

                            <button
                                type="button"
                                onClick={handleCreatePlan}
                                disabled={
                                    saving ||
                                    newPlanName.trim() === ''
                                }
                                className="px-4 py-2 rounded-lg text-sm font-medium
                                    bg-green-600 text-white
                                    hover:bg-green-700
                                    disabled:opacity-50
                                    disabled:cursor-not-allowed
                                    transition"
                            >
                                {saving ? 'Creating...' : 'Create plan'}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
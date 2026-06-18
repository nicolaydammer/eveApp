import { calculateBlueprintRuns } from "../calculations/CalculateBlueprintRuns.js";
import { getFormula } from "../helpers/GetFormula.js";

import { IndustryMaterial } from "../types/IndustryMaterial.js";
import { IndustryPayload } from "../types/IndustryPayload.js";
import { IndustrySettings } from "../types/IndustrySettings.js";
import { ProcessedIndustryNode } from "../types/ProcessedIndustryNode.js";
import { applyMaterialEfficiency } from "../calculations/ApplyMaterialEfficiency.js";

export interface ProcessedIndustryTree {
    tree: ProcessedIndustryNode[];
}

export function processIndustryTree(
    payload: IndustryPayload,
    settings: IndustrySettings,
): ProcessedIndustryTree {

    const tree = buildTree(payload);

    calculateTree(tree, settings);

    return {
        tree,
    };
}

/**
 * Phase 1
 *
 * Build the recursive tree.
 */
function buildTree(
    payload: IndustryPayload,
): ProcessedIndustryNode[] {

    return payload.requirements.materials.map(material =>
        buildNode(payload, material, 0)
    );

}

function buildNode(
    payload: IndustryPayload,
    material: IndustryMaterial,
    depth: number,
): ProcessedIndustryNode {

    const formula = getFormula(payload, material.formula_id);

    return {

        typeID: material.typeID,

        name: material.name,

        quantityPerRun: material.quantity,

        requiredUnits: 0,

        runs: null,

        outputPerRun: formula?.products[0]?.quantity ?? null,

        depth,

        children:
            formula?.materials.map(child =>
                buildNode(
                    payload,
                    child,
                    depth + 1,
                )
            ) ?? [],

    };

}

/**
 * Phase 2
 *
 * Calculate all values on the tree.
 */
function calculateTree(
    tree: ProcessedIndustryNode[],
    settings: IndustrySettings,
): void {

    for (const node of tree) {

        calculateNode(
            node,
            node.quantityPerRun,
            settings,
        );

    }

}

function calculateNode(
    node: ProcessedIndustryNode,
    incomingRequiredUnits: number,
    settings: IndustrySettings,
): void {

    const requiredUnits = applyMaterialEfficiency(
        incomingRequiredUnits,
        settings.materialEfficiency,
    );

    node.requiredUnits = requiredUnits;

    if (node.outputPerRun !== null) {

        node.runs = calculateBlueprintRuns(
            node.requiredUnits,
            node.outputPerRun,
        );

    }

    const parentRuns = node.runs ?? 1;

    for (const child of node.children) {

        const childRequiredUnits =
            child.quantityPerRun * parentRuns;

        calculateNode(
            child,
            childRequiredUnits,
            settings,
        );

    }

}
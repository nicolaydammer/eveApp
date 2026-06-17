import { IndustryMaterial } from "./types/IndustryMaterial.js";
import { IndustryPayload } from "./types/IndustryPayload.js";
import { IndustrySettings } from "./types/IndustrySettings.js";
import { ProcessedIndustryNode } from "./types/ProcessedIndustryNode.js";

import { calculateBlueprintRuns } from "./calculations/CalculateBlueprintRuns.js";
import { getFormula } from "./helpers/GetFormula.js";

export function processIndustryTree(
    payload: IndustryPayload,
    settings: IndustrySettings
): ProcessedIndustryNode[] {

    return payload.requirements.materials.map(material =>
        processMaterial(
            payload,
            material,
            material.quantity,
            0
        )
    );

}

function processMaterial(
    payload: IndustryPayload,
    material: IndustryMaterial,
    requiredUnits: number,
    depth: number
): ProcessedIndustryNode {

    const formula = getFormula(payload, material.formula_id);

    const outputPerRun =
        formula?.products[0]?.quantity ?? null;

    const runs =
        outputPerRun !== null
            ? calculateBlueprintRuns(
                requiredUnits,
                outputPerRun
            )
            : null;

    let children: ProcessedIndustryNode[] = [];

    if (formula && runs !== null) {

        children = formula.materials.map(child =>
            processMaterial(
                payload,
                child,
                child.quantity * runs,
                depth + 1
            )
        );

    }

    return {

        material,

        requiredUnits,

        runs,

        outputPerRun,

        depth,

        children

    };

}
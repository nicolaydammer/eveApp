import { IndustryFormula } from "../types/IndustryFormula.js";
import { IndustryPayload } from "../types/IndustryPayload.js";

export function getFormula(
    payload: IndustryPayload,
    formulaId: number | null
): IndustryFormula | null {

    if (formulaId === null) {
        return null;
    }

    return payload.formulas[formulaId] ?? null;

}
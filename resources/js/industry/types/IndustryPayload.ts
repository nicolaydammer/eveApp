import { IndustryFormula } from "./IndustryFormula.js";
import { IndustryMaterial } from "./IndustryMaterial.js";

export interface IndustryPayload {

    blueprintTypeID: number;

    name: string;

    activity_type: string;

    requirements: {

        time: number;

        materials: IndustryMaterial[];

    };

    formulas: Record<number, IndustryFormula>;

}
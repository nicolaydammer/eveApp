import { IndustryMaterial } from "./IndustryMaterial.js";
import { IndustryProduct } from "./IndustryProduct.js";

export interface IndustryFormula {
    blueprintTypeID: number;

    name: string;

    iconID: number | null;

    groupID: number;

    group_name: string;

    activity_type: "manufacturing" | "reaction";

    time: number;

    materials: IndustryMaterial[];

    products: IndustryProduct[];
}
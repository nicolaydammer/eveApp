import { IndustryMaterial } from "./IndustryMaterial.js";

export interface ProcessedIndustryNode {

    material: IndustryMaterial;

    requiredUnits: number;

    runs: number | null;

    outputPerRun: number | null;

    depth: number;

    children: ProcessedIndustryNode[];

}
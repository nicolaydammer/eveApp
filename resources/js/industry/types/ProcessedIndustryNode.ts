export interface ProcessedIndustryNode {

    typeID: number;

    name: string;

    quantityPerRun: number;

    requiredUnits: number;

    runs: number | null;

    outputPerRun: number | null;

    depth: number;

    children: ProcessedIndustryNode[];

}
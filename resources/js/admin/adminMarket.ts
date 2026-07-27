import axios from "@/lib/axios.js";

export interface Region {
    id: number;
    name: string;
}

export interface MarketRegions {
    regions: Region[];
    synced: number[];
}

export interface StructureMarketConfiguration {
    structure_id: number;
    character_id: number;
    character_name: string;
}

export async function getExistingRegionConfiguration(): Promise<MarketRegions> {
    const response = await axios.get("/admin/market/regions");

    return response.data;
}

export async function getExistingStructureConfiguration(): Promise<StructureMarketConfiguration[]> {
    const response = await axios.get("/admin/market/structures");

    return response.data;
}
import axios from "@/lib/axios.js";

export interface Region {
    _key: number;
    region: string;
}

export interface StructureMarketConfiguration {
    structure_id: number;
    character_id: number;
    character_name: string;
}

interface ConfigurationResponse<T> {
    configuration: T;
}

export async function getMarketRegions(
    search: string = ''
): Promise<Region[]> {
    const response = await axios.get('/eve/regions', {
        params: {
            search,
        },
    });

    return response.data;
}

export async function getExistingRegionConfiguration(): Promise<number[]> {
    const response = await axios.get<ConfigurationResponse<number[]>>(
        "/admin/market_regions"
    );

    return response.data.configuration ?? [];
}

export async function saveRegionConfiguration(
    configuration: number[]
): Promise<void> {
    await axios.post("/admin/market_regions", {
        configuration,
    });
}

export async function getExistingStructureConfiguration(): Promise<
    StructureMarketConfiguration[]
> {
    const response = await axios.get("/admin/market/structures");

    return response.data ?? [];
}
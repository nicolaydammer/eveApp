import axios from "@/lib/axios.js";

export interface System {
    _key: number;
    system: string;
}

export interface StructureMarketConfiguration {
    structure: number;
    char: number;
}

export interface StructureMarketMapping {
    structure_id: number;
    character_id: number;
    character_name: string;
}

interface ConfigurationResponse<T> {
    configuration: T;
}

export async function getMarketSystems(
    search: string = ''
): Promise<System[]> {
    const response = await axios.get('/eve/systems', {
        params: {
            search,
        },
    });

    return response.data;
}

export async function getExistingSystemConfiguration(): Promise<number[]> {
    const response = await axios.get<ConfigurationResponse<number[]>>(
        "/admin/market_regions"
    );

    return response.data.configuration ?? [];
}

export async function saveSystemConfiguration(
    configuration: number[]
): Promise<void> {
    await axios.post("/admin/market_regions", {
        configuration,
    });
}

export async function getExistingStructureConfiguration(): Promise<
    StructureMarketConfiguration[]
> {
    const response = await axios.get<
        ConfigurationResponse<StructureMarketConfiguration[]>
    >("/admin/structure_markets");

    return response.data.configuration ?? [];
}

export async function saveStructureConfiguration(
    configuration: StructureMarketConfiguration[]
): Promise<void> {
    await axios.post("/admin/structure_markets", {
        configuration,
    });
}
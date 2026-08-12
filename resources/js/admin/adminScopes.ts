import axios from "@/lib/axios.js";

export type Scope = string;

export interface ConfigurationResponse<T> {
    configuration: T;
}

export async function getScopes(): Promise<Scope[]> {
    const response = await axios.get<Scope[]>("/admin/scopes/list");

    return response.data;
}

export async function getConfiguration(): Promise<string[]> {
    const response = await axios.get<ConfigurationResponse<string[]>>(
        "/admin/esi_scopes"
    );

    return response.data.configuration ?? [];
}

export async function saveConfiguration(
    configuration: string[]
): Promise<void> {
    await axios.post("/admin/esi_scopes", {
        configuration,
    });
}
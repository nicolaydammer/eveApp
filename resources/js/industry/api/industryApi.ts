import axios from '@/lib/axios.js';
import { route } from 'ziggy-js';

import type { Blueprint } from '../types/Blueprint.js';
import type { IndustryActivity } from '../types/IndustryActivity.js';
import type { Rig, Structure } from '../types/Structure.js';
import type { System } from '../types/System.js';

export async function searchBlueprints(search: string): Promise<Blueprint[]> {

    const response = await axios.get(
        route('industry.blueprints'),
        { params: { search } },
    );

    return response.data.results;
}

export async function getIndustryActivities(): Promise<IndustryActivity[]> {
    const response = await axios.get(
        route('industry.activities')
    );

    return response.data;
}

export async function getStructures(
    activity: number,
): Promise<Structure[]> {

    const response = await axios.get(
        route('industry.structures'),
        {
            params: {
                activity,
            },
        },
    );

    return response.data;
}

export async function getRigs(
    structureId: number,
    activity: number,
): Promise<Rig[]> {

    const response = await axios.get(
        route('industry.structures.rigs'),
        {
            params: {
                structureId,
                activity,
            },
        },
    );

    return response.data;
}

export async function getSystems(search = ''): Promise<System[]> {

    const response = await axios.get(route('eve.listSystems'), {
        params: {
            search,
        },
    });

    return response.data;
}

export async function marketData() {
    const response = await axios.get(
        route('market.pricelists')
    );

    return response.data;
}

export async function referencePrices() {
    const response = await axios.get(
        route('market.referencePrices')
    );

    return response.data;
}

export async function blueprintTree(_key: number) {
    const response = await axios.get(
        route('industry.fullTree', { _key: _key })
    );

    return response.data;
}

export async function modifiersData(securityStatus: number, activity: string, rigIds: number[]) {
    const response = await axios.get(
        route('industry.modifiers'), {
        params: {
            securityStatus: securityStatus,
            activity: activity,
            rigIds: rigIds
        },
    });

    return response.data;
}

export async function systemCostIndex(systemId: number) {
    const response = await axios.get(
        route('eve.systemCostIndex', { system: systemId })
    );

    return response.data;
}

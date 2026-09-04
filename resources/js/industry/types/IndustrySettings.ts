import type { Blueprint } from './Blueprint.js';
import type { IndustryActivity } from './IndustryActivity.js';
import type { Rig, Structure } from './Structure.js';
import type { System } from './System.js';

export interface IndustrySettings {
    blueprint: Blueprint | null;
    activity: IndustryActivity | null;
    structure: Structure | null;
    rigs: (Rig | null)[];
    system: System | null;
    materialEfficiency: number;
    timeEfficiency: number;
}

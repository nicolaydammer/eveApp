export interface IndustryMaterial {
    typeID: number;

    name: string;

    iconID: number | null;

    groupID: number;

    group_name: string;

    unit_volume: number;

    unit_mass: number;

    quantity: number;

    formula_id: number | null;
}
export function calculateBlueprintRuns(
    requiredUnits: number,
    outputPerRun: number
): number {

    if (outputPerRun <= 0) {
        return 0;
    }

    return Math.ceil(requiredUnits / outputPerRun);

}
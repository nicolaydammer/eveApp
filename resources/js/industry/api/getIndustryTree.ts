import axios from "@/lib/axios.js";
import { IndustryPayload } from "../types/IndustryPayload.js";

export async function getIndustryTree(
    blueprintKey: number
): Promise<IndustryPayload> {

    const response = await axios.get(
        `/industry/full-tree/${blueprintKey}`
    );

    return response.data;
}
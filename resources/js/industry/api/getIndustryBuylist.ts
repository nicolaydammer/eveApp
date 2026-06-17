import axios from "@/lib/axios.js";
import { IndustryPayload } from "../types/IndustryPayload.js";

export async function getIndustryBuylist(
    blueprintKey: number
): Promise<IndustryPayload> {

    const response = await axios.get(
        `/industry/direct-buy/${blueprintKey}`
    );

    return response.data;
}
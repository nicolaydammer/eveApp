import { useState, useEffect, useMemo } from "react";
import { IndustryPayload } from "./types/IndustryPayload.js";
import { IndustrySettings } from "./types/IndustrySettings.js";
import { getIndustryTree } from "./api/getIndustryTree.js";
import { ProcessedIndustryTree, processIndustryTree } from "./processing/ProcessIndustryTree.js";
import IndustryTree from "./components/IndustryTree.js";

interface IndustryCalculatorProps {
    blueprintKey: number;
}

export default function IndustryCalculator({
    blueprintKey,
}: IndustryCalculatorProps) {

    const [payload, setPayload] = useState<IndustryPayload | null>(null);

    const [loading, setLoading] = useState(true);

    const [settings, setSettings] = useState<IndustrySettings>({
        materialEfficiency: 0,
        timeEfficiency: 0,
    });

    useEffect(() => {

        async function load() {

            setLoading(true);

            try {

                const payload = await getIndustryTree(blueprintKey);

                setPayload(payload);

            } finally {

                setLoading(false);

            }

        }

        load();

    }, [blueprintKey]);

    const tree: ProcessedIndustryTree = useMemo(() => {

        if (!payload) {
            return {
                tree: [],
            };
        }

        return processIndustryTree(
            payload,
            settings,
        );

    }, [payload, settings]);

    return (
        <div>

            <IndustryTree tree={tree} />

        </div>
    );

}
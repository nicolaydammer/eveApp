import { ProcessedIndustryNode } from "../types/ProcessedIndustryNode.js";
import IndustryNode from "./IndustryNode.js";

interface IndustryTreeProps {
    tree: ProcessedIndustryNode[];
}

export default function IndustryTree({
    tree,
}: IndustryTreeProps) {

    return (
        <>
            {tree.map(node => (
                <IndustryNode
                    key={node.material.typeID}
                    node={node}
                />
            ))}
        </>
    );

}
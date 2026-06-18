import { ProcessedIndustryTree } from "../types/ProcessedIndustryTree.js";
import IndustryNode from "./IndustryNode.js";

interface IndustryTreeProps {
    tree: ProcessedIndustryTree;
}

export default function IndustryTree({
    tree: { tree: nodes },
}: IndustryTreeProps) {

    return (
        <>
            {nodes.map(node => (
                <IndustryNode
                    key={node.typeID}
                    node={node}
                />
            ))}
        </>
    );

}
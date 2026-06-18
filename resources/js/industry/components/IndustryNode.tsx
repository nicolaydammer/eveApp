import { ProcessedIndustryNode } from "../types/ProcessedIndustryNode.js";

interface IndustryNodeProps {
    node: ProcessedIndustryNode;
}

export default function IndustryNode({
    node,
}: IndustryNodeProps) {

    return (

        <div
            style={{
                marginLeft: node.depth * 24
            }}
        >

            <div className="ml-4">

                <div>
                    <strong>{node.name}</strong>
                </div>

                <div>
                    Required: {node.requiredUnits.toLocaleString()}
                </div>

                {node.runs !== null && (
                    <div>
                        Runs: {node.runs}
                    </div>
                )}

            </div>

            {node.children.map(child => (

                <IndustryNode
                    key={child.typeID}
                    node={child}
                />

            ))}

        </div>

    );

}
interface Option {
    value: number | string;
    label: string;
}

interface Props {
    label: string;
    value: number | string;
    options: Option[];
    placeholder?: string;
    disabled?: boolean;
    onChange: (value: string) => void;
}

export default function SelectField({
    label,
    value,
    options,
    placeholder = 'Select...',
    disabled = false,
    onChange,
}: Props) {
    return (
        <div className="space-y-2">
            <label className="text-sm font-medium">{label}</label>

            <select
                value={value}
                disabled={disabled}
                onChange={(event) => onChange(event.target.value)}
                className="w-full rounded-md border border-zinc-300 bg-white px-3 py-2 text-sm outline-none disabled:opacity-50 dark:border-zinc-700 dark:bg-zinc-900"
            >
                <option value="">{placeholder}</option>

                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
        </div>
    );
}

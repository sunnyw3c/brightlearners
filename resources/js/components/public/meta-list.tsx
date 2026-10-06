export type MetaListItem = {
    label: string;
    value: string;
};

export function MetaList({ items }: { items: MetaListItem[] }) {
    const visible = items.filter((item) => item.value !== '');

    if (visible.length === 0) {
        return null;
    }

    return (
        <dl className="grid grid-cols-2 gap-3 text-sm sm:grid-cols-3">
            {visible.map((item) => (
                <div key={item.label}>
                    <dt className="text-muted-foreground">{item.label}</dt>
                    <dd className="font-medium">{item.value}</dd>
                </div>
            ))}
        </dl>
    );
}

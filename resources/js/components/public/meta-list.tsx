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
        <dl className="grid grid-cols-2 overflow-hidden rounded-2xl border border-border/70 bg-muted/25 text-sm sm:grid-cols-3">
            {visible.map((item) => (
                <div
                    key={item.label}
                    className="border-r border-b border-border/70 p-3.5 last:border-r-0 sm:p-4"
                >
                    <dt className="text-xs font-medium text-muted-foreground">
                        {item.label}
                    </dt>
                    <dd className="mt-1 font-bold text-foreground">
                        {item.value}
                    </dd>
                </div>
            ))}
        </dl>
    );
}

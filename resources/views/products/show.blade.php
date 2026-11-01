<x-layout :title="$product->name">
    <a class="back-link" href="{{ route('products.index') }}">← Productos</a>
    <section class="detail-card">
        <span class="badge">{{ $product->category->name }}</span>
        @if (! $product->is_active)<span class="badge warning">Borrador</span>@endif
        <h1>{{ $product->name }}</h1><p class="muted">{{ $product->brand->name }}</p>
        <p class="description">{{ $product->description }}</p>
        <h2>Variantes</h2>
        <div class="table-wrap"><table>
            <thead><tr><th>Opción</th><th>SKU</th><th>Precio</th><th>Disponibilidad</th></tr></thead>
            <tbody>
                @forelse ($product->variants->where('is_active', true) as $variant)
                    <tr><td>{{ implode(' · ', array_filter([$variant->size, $variant->color, $variant->flavor, $variant->format])) ?: 'Estándar' }}</td><td>{{ $variant->sku }}</td><td>{{ number_format($variant->price_cents / 100, 2, ',', '.') }} €</td><td>{{ $variant->stock > 0 ? 'Disponible' : 'Agotado' }}</td></tr>
                @empty
                    <tr><td colspan="4">No hay variantes disponibles.</td></tr>
                @endforelse
            </tbody>
        </table></div>
    </section>
</x-layout>

<x-layout title="Productos">
    <div class="page-heading"><p class="eyebrow">ELIGE TU PRÓXIMO PASO</p><h1>Nuestros productos</h1><p class="muted">Ropa deportiva, suplementos y alimentación fitness.</p></div>
    <div class="cards product-grid">
        @forelse ($products as $product)
            <article class="card product-card">
                <span class="badge">{{ $product->category->name }}</span>
                <h2><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h2>
                <p class="muted">{{ $product->brand->name }}</p>
                @php($price = $product->variants->where('is_active', true)->min('price_cents'))
                <p class="price">{{ $price === null ? 'Sin variantes disponibles' : 'Desde '.number_format($price / 100, 2, ',', '.').' €' }}</p>
                <a href="{{ route('products.show', $product) }}">Ver producto →</a>
            </article>
        @empty
            <p class="empty-state">Todavía no hay productos publicados.</p>
        @endforelse
    </div>
    <x-pagination :items="$products" />
</x-layout>

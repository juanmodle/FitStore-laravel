<x-layout title="Catálogo de gestión">
    <div class="page-heading"><p class="eyebrow">GESTIÓN</p><h1>Catálogo y borradores</h1><p class="muted">Consulta los productos publicados y los que todavía están en preparación.</p></div>
    <div class="table-wrap card"><table>
        <thead><tr><th>Producto</th><th>Categoría</th><th>Marca</th><th>Estado</th><th>Detalle</th></tr></thead>
        <tbody>
            @forelse ($products as $product)
                <tr><td>{{ $product->name }}</td><td>{{ $product->category->name }}</td><td>{{ $product->brand->name }}</td><td>{{ $product->is_active ? 'Publicado' : 'Borrador' }}</td><td><a href="{{ route('products.show', $product) }}">Consultar</a></td></tr>
            @empty
                <tr><td colspan="5">No hay productos.</td></tr>
            @endforelse
        </tbody>
    </table></div>
    <x-pagination :items="$products" />
</x-layout>

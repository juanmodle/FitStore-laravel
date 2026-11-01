<x-layout title="Gestión de pedidos">
    <div class="page-heading"><p class="eyebrow">GESTIÓN</p><h1>Pedidos de la tienda</h1><p class="muted">Consulta autorizada del historial de pedidos.</p></div>
    <div class="table-wrap card"><table>
        <thead><tr><th>Pedido</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Detalle</th></tr></thead>
        <tbody>
            @forelse ($orders as $order)
                <tr><td>{{ $order->number }}</td><td>{{ $order->customer_name }}</td><td>{{ $order->placed_at->format('d/m/Y') }}</td><td>{{ number_format($order->total_cents / 100, 2, ',', '.') }} €</td><td><a href="{{ route('account.orders.show', $order) }}">Ver pedido</a></td></tr>
            @empty
                <tr><td colspan="5">No hay pedidos.</td></tr>
            @endforelse
        </tbody>
    </table></div>
    <x-pagination :items="$orders" />
</x-layout>

<x-layout title="Mis pedidos">
    <div class="page-heading"><p class="eyebrow">MI CUENTA</p><h1>Mis pedidos</h1><p class="muted">Tu historial de compras.</p></div>
    <div class="table-wrap card"><table>
        <thead><tr><th>Pedido</th><th>Fecha</th><th>Estado</th><th>Total</th><th>Detalle</th></tr></thead>
        <tbody>
            @forelse ($orders as $order)
                <tr><td>{{ $order->number }}</td><td>{{ $order->placed_at->format('d/m/Y') }}</td><td>{{ ['pending' => 'Pendiente', 'paid' => 'Pagado', 'shipped' => 'Enviado', 'completed' => 'Completado', 'cancelled' => 'Cancelado'][$order->status] }}</td><td>{{ number_format($order->total_cents / 100, 2, ',', '.') }} €</td><td><a href="{{ route('account.orders.show', $order) }}">Ver pedido</a></td></tr>
            @empty
                <tr><td colspan="5">Todavía no tienes pedidos.</td></tr>
            @endforelse
        </tbody>
    </table></div>
    <x-pagination :items="$orders" />
</x-layout>

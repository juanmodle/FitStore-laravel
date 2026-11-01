<x-layout :title="'Pedido '.$order->number">
    <div class="page-heading"><p class="eyebrow">DETALLE DEL PEDIDO</p><h1>{{ $order->number }}</h1><p class="muted">{{ $order->placed_at->format('d/m/Y') }} · {{ $order->customer_name }}</p></div>
    <div class="table-wrap card"><table>
        <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio unitario</th><th>Descuento</th><th>Importe neto</th></tr></thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr><td>{{ $item->product_name }}<small>{{ $item->sku }}</small></td><td>{{ $item->quantity }}</td><td>{{ number_format($item->unit_price_cents / 100, 2, ',', '.') }} €</td><td>{{ number_format($item->discount_cents / 100, 2, ',', '.') }} €</td><td>{{ number_format($item->net_total_cents / 100, 2, ',', '.') }} €</td></tr>
            @endforeach
        </tbody>
    </table></div>
    <section class="card order-summary">
        <p>Subtotal <strong>{{ number_format($order->subtotal_cents / 100, 2, ',', '.') }} €</strong></p>
        <p>Descuento <strong>−{{ number_format($order->discount_cents / 100, 2, ',', '.') }} €</strong></p>
        <p>Envío <strong>{{ number_format($order->shipping_cents / 100, 2, ',', '.') }} €</strong></p>
        <p>Impuestos <strong>{{ number_format($order->tax_cents / 100, 2, ',', '.') }} €</strong></p>
        <p class="total">Total <strong>{{ number_format($order->total_cents / 100, 2, ',', '.') }} €</strong></p>
    </section>
</x-layout>

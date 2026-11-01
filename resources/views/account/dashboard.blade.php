<x-layout title="Mi cuenta">
    <div class="page-heading"><p class="eyebrow">TU ESPACIO</p><h1>Hola, {{ auth()->user()->name }}</h1><p class="muted">Desde aquí puedes acceder a las opciones de tu cuenta.</p></div>
    <div class="cards">
        <article class="card"><h2>Mi perfil</h2><p>Actualiza tu nombre, correo, teléfono e idioma preferido.</p><a href="{{ route('account.profile.edit') }}">Editar perfil →</a></article>
        @can('viewAny', App\Models\Order::class)
            <article class="card"><h2>Mis pedidos</h2><p>Consulta tus pedidos y el detalle de cada compra.</p><a href="{{ route('account.orders.index') }}">Ver mis pedidos →</a></article>
        @endcan
        @can('vip.access')
            <article class="card accent"><h2>Mi zona VIP</h2><p>Consulta el estado de tu suscripción.</p><a href="{{ route('account.vip') }}">Acceder →</a></article>
        @endcan
    </div>
</x-layout>

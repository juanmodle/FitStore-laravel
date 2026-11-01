<x-layout title="Mi zona VIP">
    <section class="form-card wide">
        <p class="eyebrow">MI CUENTA</p><h1>Mi zona VIP</h1>
        @if (auth()->user()->isVip())
            <p class="notice success">Tienes una suscripción VIP vigente.</p>
            <p>Válida hasta {{ auth()->user()->vipSubscriptions()->where('status', 'active')->where('starts_at', '<=', now())->where('ends_at', '>', now())->orderByDesc('ends_at')->first()->ends_at->format('d/m/Y') }}.</p>
        @else
            <p>Tu acceso a esta zona está habilitado, pero no tienes una suscripción VIP vigente.</p>
            <p class="muted">El acceso a la zona VIP no activa por sí solo las ventajas de una suscripción.</p>
        @endif
        <a href="{{ route('account.dashboard') }}">Volver a mi cuenta →</a>
    </section>
</x-layout>

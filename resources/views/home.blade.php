<x-layout title="Inicio">
    <section class="hero">
        <p class="eyebrow">TU ENTRENAMIENTO, TU RITMO</p>
        <h1>Todo empieza<br>con un buen hábito.</h1>
        <p class="hero-text">Descubre ropa deportiva, suplementos y alimentación fitness para acompañarte cada día.</p>
        <div class="actions">
            <a class="button" href="{{ route('products.index') }}">Explorar productos <span aria-hidden="true">→</span></a>
            @guest
                <a class="text-link" href="{{ route('register') }}">Crear mi cuenta</a>
            @else
                <a class="text-link" href="{{ route('account.dashboard') }}">Ir a mi cuenta</a>
            @endguest
        </div>
        <div class="tags"><span>Ropa deportiva</span><span>Suplementos</span><span>Alimentación fitness</span></div>
    </section>
    <section class="cards" aria-label="Descubre FitStore">
        <article class="card"><span class="card-number">01</span><h2>Conoce el catálogo</h2><p>Consulta las características y variantes de nuestros productos.</p><a href="{{ route('products.index') }}">Ver productos →</a></article>
        <article class="card"><span class="card-number">02</span><h2>Tu espacio personal</h2><p>Mantén tus datos al día y consulta el historial de tus pedidos.</p><a href="{{ route('account.dashboard') }}">Mi cuenta →</a></article>
        <article class="card accent"><span class="card-number">03</span><h2>A tu manera</h2><p>Organiza tus datos de contacto y preferencias desde tu perfil.</p><a href="{{ route('account.profile.edit') }}">Mi perfil →</a></article>
    </section>
</x-layout>

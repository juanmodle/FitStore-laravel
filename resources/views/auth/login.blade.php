<x-layout title="Entrar">
    <section class="form-card">
        <p class="eyebrow">BIENVENIDO DE NUEVO</p>
        <h1>Entra en tu cuenta</h1>
        <p class="muted">Consulta tus pedidos y mantén tu perfil actualizado.</p>
        <form method="POST" action="{{ route('login.store') }}" class="form-stack">
            @csrf
            <div><x-label for="email">Correo electrónico</x-label><x-input name="email" type="email" autocomplete="username" maxlength="254" required autofocus /></div>
            <div><x-label for="password">Contraseña</x-label><x-input name="password" type="password" autocomplete="current-password" required /></div>
            <x-checkbox name="remember">Recordar mi sesión</x-checkbox>
            <button type="submit" class="button full">Entrar</button>
        </form>
        <p class="form-bottom">¿Todavía no tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
    </section>
</x-layout>

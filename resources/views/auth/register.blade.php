<x-layout title="Crear cuenta">
    <section class="form-card">
        <p class="eyebrow">ÚNETE A FITSTORE</p>
        <h1>Crea tu cuenta</h1>
        <p class="muted">Un espacio para tus datos y tus pedidos.</p>
        <form method="POST" action="{{ route('register.store') }}" class="form-stack">
            @csrf
            <div><x-label for="name">Nombre</x-label><x-input name="name" autocomplete="name" maxlength="100" required autofocus /></div>
            <div><x-label for="email">Correo electrónico</x-label><x-input name="email" type="email" autocomplete="username" maxlength="254" required /></div>
            <div><x-label for="password">Contraseña</x-label><x-input name="password" type="password" autocomplete="new-password" required /><p class="hint">Al menos 8 caracteres, con letras y números.</p></div>
            <div><x-label for="password_confirmation">Repite la contraseña</x-label><x-input name="password_confirmation" type="password" autocomplete="new-password" required /></div>
            <button type="submit" class="button full">Crear cuenta</button>
        </form>
        <p class="form-bottom">¿Ya tienes cuenta? <a href="{{ route('login') }}">Entra aquí</a></p>
    </section>
</x-layout>

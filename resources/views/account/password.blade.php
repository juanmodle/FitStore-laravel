<x-layout title="Cambiar contraseña">
    <section class="form-card">
        <p class="eyebrow">SEGURIDAD</p><h1>Cambiar contraseña</h1>
        <form method="POST" action="{{ route('account.password.update') }}" class="form-stack">
            @csrf
            @method('PUT')
            <div><x-label for="current_password">Contraseña actual</x-label><x-input name="current_password" type="password" autocomplete="current-password" required /></div>
            <div><x-label for="password">Nueva contraseña</x-label><x-input name="password" type="password" autocomplete="new-password" required /><p class="hint">Al menos 8 caracteres, con letras y números.</p></div>
            <div><x-label for="password_confirmation">Repite la nueva contraseña</x-label><x-input name="password_confirmation" type="password" autocomplete="new-password" required /></div>
            <button class="button full" type="submit">Guardar contraseña</button>
        </form>
        <p class="form-bottom"><a href="{{ route('account.profile.edit') }}">Volver a mi perfil</a></p>
    </section>
</x-layout>

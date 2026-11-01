<x-layout title="Mi perfil">
    <section class="form-card wide">
        <p class="eyebrow">MI CUENTA</p><h1>Mi perfil</h1><p class="muted">Mantén tus datos de contacto actualizados.</p>
        <form method="POST" action="{{ route('account.profile.update') }}" class="form-stack">
            @csrf
            @method('PATCH')
            <div><x-label for="name">Nombre</x-label><x-input name="name" :value="$user->name" maxlength="100" autocomplete="name" required /></div>
            <div><x-label for="email">Correo electrónico</x-label><x-input name="email" type="email" :value="$user->email" maxlength="254" autocomplete="email" required /></div>
            <div><x-label for="phone">Teléfono <span class="muted">(opcional)</span></x-label><x-input name="phone" type="tel" :value="$user->profile?->phone" maxlength="30" autocomplete="tel" /></div>
            <div><x-label for="locale">Idioma preferido</x-label><x-select name="locale" :options="['es' => 'Español', 'en' => 'Inglés']" :value="$user->locale" required /></div>
            <div><x-label for="current_password">Contraseña actual</x-label><x-input name="current_password" type="password" autocomplete="current-password" /><p class="hint">Necesaria únicamente si cambias tu correo electrónico.</p></div>
            <div class="actions"><button type="submit" class="button">Guardar cambios</button><a href="{{ route('account.dashboard') }}">Volver a mi cuenta</a></div>
        </form>
        <div class="section-divider"><h2>Seguridad</h2><p><a href="{{ route('account.password.edit') }}">Cambiar mi contraseña →</a></p></div>
    </section>
</x-layout>

<x-layout title="Usuarios">
    <div class="page-heading"><p class="eyebrow">ADMINISTRACIÓN</p><h1>Usuarios</h1><p class="muted">Consulta las cuentas y filtra por rol o fecha de registro.</p></div>
    <form method="GET" action="{{ route('management.users.index') }}" class="card filters">
        <div><x-label for="role">Rol</x-label><x-select name="role" :options="['' => 'Todos los roles'] + $roles" :value="request('role')" /></div>
        <div><x-label for="registered_from">Registrado desde</x-label><x-date name="registered_from" :value="request('registered_from')" /></div>
        <div><x-label for="registered_until">Registrado hasta</x-label><x-date name="registered_until" :value="request('registered_until')" /></div>
        <div class="filter-check"><x-checkbox name="verified_only" :checked="request()->boolean('verified_only')">Solo correo verificado</x-checkbox></div>
        <div class="actions"><button type="submit" class="button">Filtrar</button><a href="{{ route('management.users.index') }}">Limpiar filtros</a></div>
    </form>
    <div class="table-wrap card"><table>
        <thead><tr><th>Nombre</th><th>Correo</th><th>Roles</th><th>Registro</th></tr></thead>
        <tbody>
            @forelse ($users as $user)
                <tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->roles->pluck('name')->join(', ') ?: 'Sin rol' }}</td><td>{{ $user->created_at?->format('d/m/Y') }}</td></tr>
            @empty
                <tr><td colspan="4">No hay usuarios que coincidan con los filtros.</td></tr>
            @endforelse
        </tbody>
    </table></div>
    <x-pagination :items="$users" />
</x-layout>

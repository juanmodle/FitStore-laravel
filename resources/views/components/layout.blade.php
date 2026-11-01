@props(['title' => 'FitStore'])
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} · FitStore</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <a class="skip-link" href="#contenido">Ir al contenido</a>
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ route('home') }}">FIT<span>STORE</span></a>
            <nav aria-label="Navegación principal">
                <a href="{{ route('products.index') }}">Productos</a>
                @auth
                    <a href="{{ route('account.dashboard') }}">Mi cuenta</a>
                    @can('catalog.view-drafts')
                        <a href="{{ route('management.catalog.index') }}">Catálogo de gestión</a>
                    @endcan
                    @can('orders.view-all')
                        <a href="{{ route('management.orders.index') }}">Gestión de pedidos</a>
                    @endcan
                    @can('users.view')
                        <a href="{{ route('management.users.index') }}">Usuarios</a>
                    @endcan
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="nav-button" type="submit">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Entrar</a>
                    <a class="button small" href="{{ route('register') }}">Crear cuenta</a>
                @endauth
            </nav>
        </div>
    </header>
    <main id="contenido" class="container">
        @if (session('status'))
            <div class="notice success" role="status">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="notice error" role="alert">Revisa los campos indicados y vuelve a intentarlo.</div>
        @endif
        {{ $slot }}
    </main>
    <footer class="site-footer">FitStore · Productos para tu entrenamiento</footer>
</body>
</html>

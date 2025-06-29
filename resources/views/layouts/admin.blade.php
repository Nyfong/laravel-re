<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white min-h-screen p-4">
            <h2 class="text-2xl font-bold mb-6">Admin Panel</h2>
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('dashboard.admin') }}" class="block p-2 hover:bg-gray-700 rounded {{ request()->routeIs('dashboard.admin') ? 'bg-gray-700' : '' }}">Dashboard</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('dashboard.lowstock-purchases') }}" class="block p-2 hover:bg-gray-700 rounded {{ request()->routeIs('dashboard.lowstock-purchases') ? 'bg-gray-700' : '' }}">Low Stock & Purchases</a>
                    </li>
                    <li class="mb-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left p-2 hover:bg-gray-700 rounded">Logout</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- Main Content -->
        <div class="flex-1 p-4">
            @yield('content')
        </div>
    </div>
</body>
</html>
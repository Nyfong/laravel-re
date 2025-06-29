<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="p-4">
            <h2 class="text-xl font-bold">Admin Dashboard</h2>
            <button id="closeSidebar" class="md:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <nav class="mt-4">
            <ul>
                <li><a href="{{ route('dashboard.admin') }}" class="block p-4 hover:bg-gray-700">Dashboard</a></li>
                <li><a href="{{ route('dashboard.lowstock-products') }}" class="block p-4 hover:bg-gray-700">Low Stock Products</a></li>
                <li><a href="{{ route('dashboard.purchase-history') }}" class="block p-4 hover:bg-gray-700">Purchase History</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left p-4 hover:bg-gray-700">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
    <div id="sidebarOverlay" class="fixed inset-0 bg-black opacity-50 hidden md:hidden"></div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:ml-64">
        <div class="container mx-auto">
            <button id="toggleSidebar" class="md:hidden text-gray-800 focus:outline-none mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
            <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-xl font-semibold">Total Orders</h2>
                    <p class="text-2xl">{{ $totalOrders }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-xl font-semibold">Low Stock Products</h2>
                    <p class="text-2xl">{{ $totalLowStock }}</p>
                </div>
            </div>

            <!-- Search -->
            <div class="mb-6">
                <form method="GET" action="{{ route('dashboard.admin') }}">
                    <div class="flex space-x-2">
                        <input type="text" name="search" class="p-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by product or category (e.g., phone, Electronics)" value="{{ request('search') }}">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">Search</button>
                    </div>
                </form>
            </div>

            <!-- Low Stock Products Table -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-semibold mb-4">Low Stock Products</h2>
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-3 text-left">Product Name</th>
                            <th class="p-3 text-left">Category</th>
                            <th class="p-3 text-left">Stock</th>
                            <th class="p-3 text-left">Last Import</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockProducts as $product)
                            <tr class="border-b">
                                <td class="p-3">{{ $product->name }}</td>
                                <td class="p-3">{{ $product->category->name }}</td>
                                <td class="p-3">{{ $product->stock }}</td>
                                <td class="p-3">{{ $product->last_import_date ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('orders.export') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200">Export Orders to Excel</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4 mt-auto">
        <div class="container mx-auto text-center">
            <p>© {{ date('Y') }} Inventory System. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const toggleSidebar = document.getElementById('toggleSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebarFunc() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        toggleSidebar.addEventListener('click', openSidebar);
        closeSidebar.addEventListener('click', closeSidebarFunc);
        overlay.addEventListener('click', closeSidebarFunc);
    </script>
</body>
</html>
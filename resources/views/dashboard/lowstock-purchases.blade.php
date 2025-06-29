<!DOCTYPE html>
<html>
<head>
    <title>Low Stock & Purchases</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Toggle Button -->
    <button id="toggleSidebar" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-gray-800 text-white rounded focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>
    <div class="flex">
        <!-- Sidebar -->
        @include('dashboard.partials.sidebar')
        <!-- Main Content -->
        <div class="flex-1 container mx-auto p-4 md:ml-64">
            <h1 class="text-3xl font-bold mb-6">Low Stock & Purchases</h1>
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Low Stock Products</h2>
                <form method="GET" action="{{ route('dashboard.lowstock-purchases') }}">
                    <input type="text" name="search" class="p-2 border rounded" placeholder="Enter any part of product or category name (e.g., phone, Electronics)" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
                </form>
                <table class="w-full border-collapse mt-4">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">Product</th>
                            <th class="p-2">Category</th>
                            <th class="p-2">Stock</th>
                            <th class="p-2">Threshold</th>
                            <th class="p-2">Last Import</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockProducts as $product)
                            <tr>
                                <td class="p-2 border">{{ $product->name }}</td>
                                <td class="p-2 border">{{ $product->category->name }}</td>
                                <td class="p-2 border">{{ $product->stock }}</td>
                                <td class="p-2 border">{{ $product->low_stock_threshold }}</td>
                                <td class="p-2 border">{{ $product->last_import_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $lowStockProducts->links() }}
            </div>
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Purchase History</h2>
                <form method="GET" action="{{ route('dashboard.lowstock-purchases') }}">
                    <div class="flex space-x-4">
                        <select name="status" class="p-2 border rounded">
                            <option value="All" {{ request('status') == 'All' || !request('status') ? 'selected' : '' }}>All</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                        <input type="text" name="search" class="p-2 border rounded" placeholder="Enter any part of order ID or product name (e.g., 1, phone)" value="{{ request('search') }}">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
                    </div>
                </form>
                <table class="w-full border-collapse mt-4">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">Order ID</th>
                            <th class="p-2">Product</th>
                            <th class="p-2">Quantity</th>
                            <th class="p-2">Order Date</th>
                            <th class="p-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="p-2 border">{{ $order->id }}</td>
                                <td class="p-2 border">{{ $order->product->name }}</td>
                                <td class="p-2 border">{{ $order->quantity }}</td>
                                <td class="p-2 border">{{ $order->order_date }}</td>
                                <td class="p-2 border">{{ $order->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
    <script>
        const toggleSidebar = document.getElementById('toggleSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        toggleSidebar.addEventListener('click', openSidebar);
        closeSidebar.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);
    </script>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Products - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Inventory System</h1>
            <ul class="flex space-x-4">
                <li><a href="{{ route('products.index') }}" class="hover:text-gray-300">Home</a></li>
                <li><a href="{{ route('dashboard.admin') }}" class="hover:text-gray-300">Admin Dashboard</a></li>
                <li><a href="{{ route('dashboard.lowstock-products') }}" class="hover:text-gray-300">Low Stock Products</a></li>
                <li><a href="{{ route('dashboard.purchase-history') }}" class="hover:text-gray-300">Purchase History</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:text-gray-300">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-4 flex-grow">
        <h1 class="text-3xl font-bold mb-6 text-center">Low Stock Products</h1>
        
        <!-- Search -->
        <div class="mb-6">
            <form method="GET" action="{{ route('dashboard.lowstock-products') }}">
                <div class="flex space-x-2">
                    <input type="text" name="search" class="p-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by product or category (e.g., phone, Electronics)" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">Search</button>
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
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
            <div class="mt-6">
                {{ $lowStockProducts->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4 mt-auto">
        <div class="container mx-auto text-center">
            <p>© {{ date('Y') }} Inventory System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Admin Dashboard</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
            </form>
        </div>
        <div class="mb-4">
            <form method="GET" action="{{ route('dashboard.admin') }}">
                <input type="text" name="search" class="p-2 border rounded" placeholder="Enter any part of product or category name (e.g., phone, Electronics)" value="{{ request('search') }}">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
            </form>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-semibold">Total Orders</h2>
                <p class="text-2xl">{{ $totalOrders }}</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-semibold">Low Stock Products</h2>
                <p class="text-2xl">{{ $totalLowStock }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Low Stock Products</h2>
            <table class="w-full border-collapse">
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
        <a href="{{ route('orders.export') }}" class="bg-green-500 text-white px-4 py-2 rounded">Export Orders to Excel</a>
    </div>
</body>
</html>
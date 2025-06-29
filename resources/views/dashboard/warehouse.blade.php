<!DOCTYPE html>
<html>
<head>
    <title>Warehouse Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Warehouse Dashboard</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
            </form>
        </div>
        <div class="mb-4">
            <form method="GET" action="{{ route('dashboard.warehouse') }}">
                <input type="text" name="search" class="p-2 border rounded" placeholder="Search products or categories" value="{{ request('search') }}">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
            </form>
        </div>
        <div class="bg-white p-4 rounded shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Log Stock Import</h2>
            <form method="POST" action="{{ route('stock.import') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700">Product</label>
                    <select name="product_id" class="w-full p-2 border rounded" required>
                        @foreach(\App\Models\Product::all() as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Quantity</label>
                    <input type="number" name="quantity" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Import Date</label>
                    <input type="date" name="import_date" class="w-full p-2 border rounded" required>
                </div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Log Import</button>
            </form>
            @if (session('success'))
                <div class="text-green-500 mt-2">{{ session('success') }}</div>
            @endif
        </div>
        <div class="bg-white p-4 rounded shadow">
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
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Non-Purchased Products - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Top Navigation -->
    <nav class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Inventory System</h1>
            <ul class="flex space-x-4">
                <li><a href="{{ route('products.index') }}" class="hover:text-gray-300">Home</a></li>
                <li><a href="{{ route('dashboard.admin') }}" class="hover:text-gray-300 {{ Route::is('dashboard.admin') ? 'border-b-2 border-blue-500' : '' }}">Admin Dashboard</a></li>
                <li><a href="{{ route('dashboard.lowstock-products') }}" class="hover:text-gray-300 {{ Route::is('dashboard.lowstock-products') ? 'border-b-2 border-blue-500' : '' }}">Low Stock Products</a></li>
                <li><a href="{{ route('dashboard.purchase-history') }}" class="hover:text-gray-300 {{ Route::is('dashboard.purchase-history') ? 'border-b-2 border-blue-500' : '' }}">Purchase History</a></li>
                <li><a href="{{ route('dashboard.non-purchased-products') }}" class="hover:text-gray-300 {{ Route::is('dashboard.non-purchased-products') ? 'border-b-2 border-blue-500' : '' }}">Non-Purchased Products</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:text-red-300">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-6 flex-grow">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Non-Purchased Products</h1>
        
        <!-- Search -->
        <div class="mb-6">
            <form method="GET" action="{{ route('dashboard.non-purchased-products') }}">
                <div class="flex space-x-2">
                    <input type="text" name="search" class="p-3 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by product name or category" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition duration-200">Search</button>
                </div>
            </form>
        </div>

        <!-- Non-Purchased Products Table -->
        <div class="bg-white p-6 rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-200">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 text-left text-gray-600">Product Name</th>
                        <th class="p-3 text-left text-gray-600">Category</th>
                        <th class="p-3 text-left text-gray-600">Stock</th>
                        <th class="p-3 text-left text-gray-600">Last Import</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nonPurchasedProducts as $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $product->name }}</td>
                            <td class="p-3">{{ $product->category->name }}</td>
                            <td class="p-3">{{ $product->stock }}</td>
                            <td class="p-3">{{ $product->last_import_date ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-6">
                {{ $nonPurchasedProducts->appends(request()->query())->links() }}
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
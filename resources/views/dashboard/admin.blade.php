<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Top Navigation -->
    <nav class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Inventory System</h1>
            <ul class="flex space-x-4">
                <li><a href="{{ route('products.index') }}" class="hover:text-gray-300 {{ Route::is('products.index') ? 'border-b-2 border-blue-500' : '' }}">Home</a></li>
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
    <div class="flex-1 p-6">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Admin Dashboard</h1>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-200">
                    <h2 class="text-xl font-semibold text-gray-700">Total Orders</h2>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalOrders }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-200">
                    <h2 class="text-xl font-semibold text-gray-700">Low Stock Products</h2>
                    <p class="text-3xl font-bold text-red-600">{{ $totalLowStock }}</p>
                </div>
            </div>

            <!-- Stock Request Form -->
            <div class="bg-white p-6 rounded-lg shadow-xl mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Request Stock</h2>
                <form method="POST" action="{{ route('stock.request') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="product_id" class="block text-gray-700">Product</label>
                        <select name="product_id" id="product_id" class="p-3 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="quantity" class="block text-gray-700">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="p-3 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required min="1">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition duration-200">Submit Request</button>
                </form>
            </div>

            <!-- Search and Toggle -->
            <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <form method="GET" action="{{ route('dashboard.admin') }}" class="flex-1">
                    <div class="flex space-x-2">
                        <input type="text" name="search" class="p-3 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by product name or category" value="{{ request('search') }}">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition duration-200">Search</button>
                    </div>
                </form>
                <div class="flex space-x-2">
                    <button id="rowView" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Row View</button>
                    <button id="gridView" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-200">Grid View</button>
                </div>
            </div>

            <!-- Row View (Table) -->
            <div id="rowViewContainer" class="bg-white p-6 rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-200">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">All Products</h2>
                @if ($products->isEmpty())
                    <p class="text-center text-gray-500">No products found.</p>
                @else
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 text-left text-gray-600">ID</th>
                                <th class="p-3 text-left text-gray-600">Name</th>
                                <th class="p-3 text-left text-gray-600">Category</th>
                                <th class="p-3 text-left text-gray-600">Stock</th>
                                <th class="p-3 text-left text-gray-600">Low Stock Threshold</th>
                                <th class="p-3 text-left text-gray-600">Last Import Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">{{ $product->id }}</td>
                                    <td class="p-3">{{ $product->name }}</td>
                                    <td class="p-3">{{ $product->category ? $product->category->name : 'N/A' }}</td>
                                    <td class="p-3">{{ $product->stock }}</td>
                                    <td class="p-3">{{ $product->low_stock_threshold }}</td>
                                    <td class="p-3">{{ $product->last_import_date ? $product->last_import_date->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-6">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

            <!-- Grid View (Cards) -->
            <div id="gridViewContainer" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @if ($products->isEmpty())
                    <p class="text-center text-gray-500 col-span-full">No products found.</p>
                @else
                    @foreach ($products as $product)
                        <div class="bg-white p-6 rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-200">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600"><strong>ID:</strong> {{ $product->id }}</p>
                            <p class="text-gray-600"><strong>Category:</strong> {{ $product->category ? $product->category->name : 'N/A' }}</p>
                            <p class="text-gray-600"><strong>Stock:</strong> {{ $product->stock }}</p>
                            <p class="text-gray-600"><strong>Low Stock Threshold:</strong> {{ $product->low_stock_threshold }}</p>
                            <p class="text-gray-600"><strong>Last Import Date:</strong> {{ $product->last_import_date ? $product->last_import_date->format('Y-m-d') : 'N/A' }}</p>
                        </div>
                    @endforeach
                    <div class="col-span-full mt-6">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4 mt-auto">
        <div class="container mx-auto text-center">
            <p>© {{ date('Y') }} Inventory System. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const rowViewButton = document.getElementById('rowView');
        const gridViewButton = document.getElementById('gridView');
        const rowViewContainer = document.getElementById('rowViewContainer');
        const gridViewContainer = document.getElementById('gridViewContainer');

        rowViewButton.addEventListener('click', () => {
            rowViewContainer.classList.remove('hidden');
            gridViewContainer.classList.add('hidden');
            rowViewButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
            rowViewButton.classList.remove('bg-gray-600', 'hover:bg-gray-700');
            gridViewButton.classList.add('bg-gray-600', 'hover:bg-gray-700');
            gridViewButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        });

        gridViewButton.addEventListener('click', () => {
            gridViewContainer.classList.remove('hidden');
            rowViewContainer.classList.add('hidden');
            gridViewButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
            gridViewButton.classList.remove('bg-gray-600', 'hover:bg-gray-700');
            rowViewButton.classList.add('bg-gray-600', 'hover:bg-gray-700');
            rowViewButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        });
    </script>
</body>
</html>
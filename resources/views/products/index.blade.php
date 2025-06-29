<!DOCTYPE html>
<html>
<head>
    <title>All Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Inventory System</h1>
            <ul class="flex space-x-4">
                <li><a href="{{ route('products.index') }}" class="hover:text-gray-300">Home</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-gray-300">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-4 flex-grow">
        <h1 class="text-3xl font-bold mb-6">All Products</h1>
        
        <!-- Search and Filter -->
        <div class="mb-6 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
            <form method="GET" action="{{ route('products.index') }}" class="flex-1">
                <div class="flex space-x-2">
                    <input type="text" name="search" class="p-2 border rounded w-full" placeholder="Search by product or category (e.g., phone, Electronics)" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
                </div>
            </form>
            <form method="GET" action="{{ route('products.index') }}">
                <select name="category_id" class="p-2 border rounded" onchange="this.form.submit()">
                    <option value="All" {{ request('category_id') == 'All' || !request('category_id') ? 'selected' : '' }}>All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
            </form>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 ">
            @foreach($products as $product)
                <div class="bg-white p-4 rounded-xl shadow">
                    <img src="https://www.tcbap.org/global_graphics/default-store-350x350.jpg" alt="product-image" class="w-full h-48 object-cover mb-4 rounded">
                    <h2 class="text-xl font-semibold">{{ $product->name }}</h2>
                    <p class="text-gray-600">Category: {{ $product->category->name }}</p>
                    <p class="text-gray-600">Stock: {{ $product->stock }}</p>
                    <p class="text-gray-600">Last Import: {{ $product->last_import_date ?? 'N/A' }}</p>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4 mt-auto">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} Inventory System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
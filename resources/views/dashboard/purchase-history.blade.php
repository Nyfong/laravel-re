<!DOCTYPE html>
<html>
<head>
    <title>Purchase History - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Navbar -->
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
    <div class="container mx-auto p-4 flex-grow">
        <h1 class="text-3xl font-bold mb-6 text-center">Purchase History</h1>
        
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

        <!-- Search and Filter -->
        <div class="mb-6 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
            <form method="GET" action="{{ route('dashboard.purchase-history') }}" class="flex-1">
                <div class="flex space-x-2">
                    <input type="text" name="search" class="p-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter order ID or product name (e.g., 1, phone)" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">Search</button>
                </div>
            </form>
            <form method="GET" action="{{ route('dashboard.purchase-history') }}">
                <select name="status" class="p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                    <option value="All" {{ request('status') == 'All' || !request('status') ? 'selected' : '' }}>All</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
            </form>
        </div>

        <!-- Orders Table -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            @if ($orders->isEmpty())
                <p class="text-center text-gray-500">No orders found.</p>
            @else
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-3 text-left">Order ID</th>
                            <th class="p-3 text-left">Product</th>
                            <th class="p-3 text-left">Quantity</th>
                            <th class="p-3 text-left">Order Date</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="border-b">
                                <td class="p-3">{{ $order->id }}</td>
                                <td class="p-3">{{ $order->product ? $order->product->name : 'N/A' }}</td>
                                <td class="p-3">{{ $order->quantity }}</td>
                                <td class="p-3">{{ $order->order_date ? $order->order_date->format('Y-m-d') : 'N/A' }}</td>
                                <td class="p-3">{{ $order->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-6">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
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
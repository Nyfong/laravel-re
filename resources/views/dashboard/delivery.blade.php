<!DOCTYPE html>
<html>
<head>
    <title>Delivery Dashboard - Inventory System</title>
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
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Delivery Dashboard</h1>
        
        <!-- Feedback Messages -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="mb-6 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
            <form method="GET" action="{{ route('dashboard.delivery') }}" class="flex-1">
                <div class="flex space-x-2">
                    <input type="text" name="search" class="p-3 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by order ID or product name" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition duration-200">Search</button>
                </div>
            </form>
            <form method="GET" action="{{ route('dashboard.delivery') }}">
                <select name="status" class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                    <option value="All" {{ request('status') == 'All' || !request('status') ? 'selected' : '' }}>All</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
            </form>
        </div>

        <!-- Orders Table -->
        <div class="bg-white p-6 rounded-lg shadow-xl hover:shadow-2xl transition-shadow duration-200">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 text-left text-gray-600">Order ID</th>
                        <th class="p-3 text-left text-gray-600">Product</th>
                        <th class="p-3 text-left text-gray-600">Quantity</th>
                        <th class="p-3 text-left text-gray-600">Order Date</th>
                        <th class="p-3 text-left text-gray-600">Status</th>
                        <th class="p-3 text-left text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $order->id }}</td>
                            <td class="p-3">{{ $order->product->name }}</td>
                            <td class="p-3">{{ $order->quantity }}</td>
                            <td class="p-3">{{ $order->order_date }}</td>
                            <td class="p-3">{{ $order->status }}</td>
                            <td class="p-3">
                                <form action="{{ route('order.updateStatus', $order) }}" method="POST" class="flex space-x-2 items-center">
                                    @csrf
                                    @method('POST')
                                    <input type="number" name="quantity" value="{{ $order->quantity }}" min="1" class="p-2 border rounded w-20 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <select name="status" class="p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                    <input type="date" name="order_date" value="{{ $order->order_date ?? now()->toDateString() }}" class="p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Update</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-6">
                {{ $orders->appends(request()->query())->links() }}
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
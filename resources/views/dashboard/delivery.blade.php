<!DOCTYPE html>
<html>
<head>
    <title>Delivery Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Delivery Dashboard</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
            </form>
        </div>
        <div class="mb-4">
            <form method="GET" action="{{ route('dashboard.delivery') }}">
                <div class="flex space-x-4">
                    <select name="status" class="p-2 border rounded">
                        <option value="All" {{ request('status') == 'All' || !request('status') ? 'selected' : '' }}>All</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                    <input type="text" name="search" class="p-2 border rounded" placeholder="Search by order ID or product name" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
                </div>
            </form>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">Orders</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">Order ID</th>
                        <th class="p-2">Product</th>
                        <th class="p-2">Quantity</th>
                        <th class="p-2">Order Date</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Action</th>
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
                            <td class="p-2 border">
                                <form method="POST" action="{{ route('order.updateStatus', $order) }}">
                                    @csrf
                                    <select name="status" class="p-1 border rounded">
                                        <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                    <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Update</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $orders->links() }}
            @if (session('success'))
                <div class="text-green-500 mt-2">{{ session('success') }}</div>
            @endif
        </div>
    </div>
</body>
</html>
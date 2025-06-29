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
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">Recent Orders</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">Order ID</th>
                        <th class="p-2">Product</th>
                        <th class="p-2">Quantity</th>
                        <th class="p-2">Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="p-2 border">{{ $order->id }}</td>
                            <td class="p-2 border">{{ $order->product->name }}</td>
                            <td class="p-2 border">{{ $order->quantity }}</td>
                            <td class="p-2 border">{{ $order->order_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
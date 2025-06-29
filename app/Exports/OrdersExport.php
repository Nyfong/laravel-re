<?php
namespace App\Exports;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::with('product')->get()->map(function ($order) {
            return [
                'Order ID' => $order->id,
                'Product' => $order->product->name,
                'Quantity' => $order->quantity,
                'Order Date' => $order->order_date,
            ];
        });
    }

    public function headings(): array
    {
        return ['Order ID', 'Product', 'Quantity', 'Order Date'];
    }
}
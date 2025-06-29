<?php
namespace App\Exports;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::with('product')->get();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Product Name',
            'Quantity',
            'Order Date',
            'Status',
        ];
    }
}
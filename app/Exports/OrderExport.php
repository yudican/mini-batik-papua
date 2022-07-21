<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;

class OrderExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $title = null;

    public function __construct($title = null)
    {
        $this->title = 'Laporan Transaksi Tanggal ' . date('d-m-Y');
    }

    public function query()
    {
        return Order::query();
    }

    public function map($row): array
    {
        return [
            $row->kode_order,
            $row->user->name,
            'Rp ' . number_format($row->total_order),
            $row->metode_pembayaran,
            $row->kode_unik,
            $row->tanggal_order,
            $row->paymentMethod ? $row->paymentMethod->nama_bank : '-',
            $row->status,
        ];
    }

    public function headings(): array
    {
        return [
            'Kode Order',
            'Nama Pelanggan',
            'Total Bayar',
            'Type Pembayaran',
            'Kode Unik',
            'Tanggal Order',
            'Metode Pembayaran',
            'Status',
        ];
    }


    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }
}

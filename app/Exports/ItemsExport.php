<?php
namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Ambil data item beserta relasi kategorinya agar tidak kosong
        return Item::with('category')->get();
    }

    // Menentukan judul kolom paling atas
    public function headings(): array
    {
        return [
            'No',
            'Category',
            'Nama',
            'Total',
            'Repair',
            'landing',
        ];
    }

    // Memetakan data mana yang masuk ke kolom mana
    public function map($item): array
    {
        return [
            $item->id,
            $item->category->name ?? 'Tanpa Kategori',
            $item->name,
            $item->total,
            $item->repair == 0 ? '-' : $item->repair, // 🔥 fix di sini
            $item->created_at->format('d-m-Y'),
        ];
    }
}
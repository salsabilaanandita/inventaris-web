<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
    }

        public function headings(): array
    {
        return [
            'No',
            'Name',
            'Email',
            'Password',
        ];
    }

    // Memetakan data mana yang masuk ke kolom mana
    public function map($item): array
    {
        return [
            $item->id,
            $item->name,
            $item->email,
            $item->password,
        ];
    }
}

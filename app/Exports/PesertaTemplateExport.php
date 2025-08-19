<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PesertaTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'nama',
            'email',
            'npm',
            'no_hp',
            'password',
            'prodi',
            'kelas',
        ];
    }
}
<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PanitiaTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'nama',
            'email',
            'no_hp',
            'jabatan',
            'divisi',
        ];
    }
}
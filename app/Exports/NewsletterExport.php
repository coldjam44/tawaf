<?php

namespace App\Exports;

use App\Models\Newsletter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class NewsletterExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Newsletter::orderBy('created_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Message',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param Newsletter $newsletter
     * @return array
     */
    public function map($newsletter): array
    {
        // Clean phone number - remove any non-numeric characters except +
        $phone = $newsletter->phone ?? '-';
        if ($phone !== '-') {
            // Keep only numbers and + sign
            $phone = preg_replace('/[^0-9+]/', '', $phone);
        }
        
        return [
            $newsletter->id,
            $newsletter->name ?? '-',
            $newsletter->email ?? '-',
            $phone,
            $newsletter->message ?? '-',
            $newsletter->created_at->format('Y-m-d H:i:s'),
            $newsletter->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 20,  // Name
            'C' => 30,  // Email
            'D' => 20,  // Phone
            'E' => 50,  // Message
            'F' => 20,  // Created At
            'G' => 20,  // Updated At
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'D' => '0',  // Phone column as number format
        ];
    }
}

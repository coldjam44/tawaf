<?php

namespace App\Exports;

use App\Models\ContactMessage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ContactMessagesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ContactMessage::orderBy('created_at', 'desc')->get();
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
            'Status',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param ContactMessage $message
     * @return array
     */
    public function map($message): array
    {
        // Clean phone number - remove any non-numeric characters except +
        $phone = $message->phone ?? '-';
        if ($phone !== '-') {
            // Keep only numbers and + sign
            $phone = preg_replace('/[^0-9+]/', '', $phone);
        }
        
        return [
            $message->id,
            $message->name,
            $message->email,
            $phone,
            $message->message,
            $message->is_read ? 'Read' : 'Unread',
            $message->created_at->format('Y-m-d H:i:s'),
            $message->updated_at->format('Y-m-d H:i:s'),
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
            'F' => 15,  // Status
            'G' => 20,  // Created At
            'H' => 20,  // Updated At
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

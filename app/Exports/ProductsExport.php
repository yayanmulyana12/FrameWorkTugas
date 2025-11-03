<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProductsExport implements FromCollection, WithHeadings, WithEvents
{
    /**
     * Ambil data dari tabel product sesuai struktur database kamu
     */
    public function collection()
    {
        return Product::select(
            'product_name as name',
            'unit',
            'type as category',
            'information as description',
            'qty as stock',
            'producer as supplier',
            'created_at'
        )->get();
    }

    /**
     * Header kolom Excel (judul kolom)
     */
    public function headings(): array
    {
        return [
            'Name',
            'Unit',
            'Category',
            'Description',
            'Stock',
            'Supplier',
            'Barang Masuk', // created_at
        ];
    }

    /**
     * Format, styling, dan logo Excel
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();


                $drawing = new Drawing();
                $drawing->setName('Company Logo');
                $drawing->setDescription('Logo Perusahaan');
                $drawing->setPath(public_path('images/logo.png'));
                $drawing->setHeight(80);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(10);
                $drawing->setOffsetY(-150);
                $drawing->setWorksheet($sheet);

                // Tambah ruang di atas tabel
                $sheet->insertNewRowBefore(1, 7);

                // Judul utama & subjudul
                $sheet->setCellValue('C1', 'PT MILKO BEVERAGE INDUSTRY');
                $sheet->setCellValue('C2', 'REKAP MUTASI STOCK BULANAN');
                $sheet->mergeCells('C1:G1');
                $sheet->mergeCells('C2:G2');

                // Ambil tanggal paling awal dari created_at di DB
                $startPeriod = \App\Models\Product::min('created_at')
                    ? date('d F Y', strtotime(\App\Models\Product::min('created_at')))
                    : now()->format('d F Y');

                // Ambil tanggal hari ini
                $endPeriod = now()->format('d F Y');

                // Tulis ke Excel
                $sheet->mergeCells('C3:G3');
                $sheet->setCellValue('C3', "Periode: {$startPeriod} s/d {$endPeriod}");
                $sheet->getStyle('C3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Gaya huruf judul
                $sheet->getStyle('C1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(13);
                $sheet->getStyle('C1:C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Otomatis lebar kolom
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Header tabel
                $sheet->getStyle('A8:G8')->getFont()->setBold(true);
                $sheet->getStyle('A8:G8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A8:G8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // === Border hanya untuk data ===
                $dataStart = 8; // header di baris 8
                $dataEnd = $sheet->getHighestDataRow(); // baris terakhir yang berisi data
    
                $sheet->getStyle("A{$dataStart}:G{$dataEnd}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // === Tambah catatan bawah ===
                $noteRow = $dataEnd + 2;
                // === Catatan bawah dengan tanggal cetak ===
                $noteRow = $dataEnd + 2;
                $tanggalCetak = now()->format('d F Y, H:i');
                $sheet->setCellValue("A{$noteRow}", "* Data ini bersifat rahasia. Tanggal cetak {$tanggalCetak}.");
                $sheet->getStyle("A{$noteRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $sheet->mergeCells("A{$noteRow}:G{$noteRow}");

                // === Tanda tangan bagian logistik ===
                // === Tanda tangan bagian logistik ===
                $sheet->setCellValue("G" . ($noteRow + 2), 'Diketahui,');
                $sheet->setCellValue("G" . ($noteRow + 8), '(____________________)');
                $sheet->mergeCells("G" . ($noteRow + 8) . ":I" . ($noteRow + 8));

                // "Kepala Logistik" di bawah garis
                $sheet->setCellValue("G" . ($noteRow + 9), 'Kepala Logistik');

                $sheet->mergeCells("G" . ($noteRow + 7) . ":G" . ($noteRow + 7));
            },
        ];
    }
}

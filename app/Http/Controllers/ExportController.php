<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function exportToCsv()
    {
        // Query data dari database
        $data = DB::table('keyword_user_tracks')->get(); // Ganti 'your_table_name' dengan tabel Anda

        // Konversi data ke array
        $dataArray = [];
        $headerSet = false;

        foreach ($data as $row) {
            $rowArray = (array) $row;

            // Tambahkan header jika belum ada
            if (!$headerSet) {
                $dataArray[] = array_keys($rowArray);
                $headerSet = true;
            }

            // Tambahkan data baris
            $dataArray[] = array_values($rowArray);
        }

        // Buat nama file CSV
        $fileName = 'export_' . date('Ymd_His') . '.csv';

        // Generate file CSV
        $csvContent = $this->arrayToCsv($dataArray);

        // Kirim file CSV sebagai respons
        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }

    private function arrayToCsv(array $data): string
    {
        // Konversi array ke CSV string
        $csvContent = '';
        $file = fopen('php://temp', 'r+');

        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        rewind($file);
        $csvContent = stream_get_contents($file);
        fclose($file);

        return $csvContent;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\DataSampah;
use App\Models\HistoryPembelian;
use App\Models\HistoryPenjualan;
use App\Models\Nasabah;
use Illuminate\Console\Command;

class RepairPenjualan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repair-penjualan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pembelians = HistoryPembelian::all();
        foreach ($pembelians as $key => $pembelian) {
            $penjualan = HistoryPenjualan::where('id_pembelian', $pembelian->id)->first();
            if (!$penjualan) {
                $dataNasabah = Nasabah::find($pembelian->id_nasabah);
                $dataSampah = DataSampah::find($pembelian->id_sampah);
                $totalHargaJual = $this->convertRupiahToInteger($dataSampah->harga_jual);
                $formDataPenjualan = [
                    'id_sampah' => $pembelian->id_sampah,
                    'nama_sampah' => $dataSampah->nama_sampah,
                    'harga' => $this->convertRupiahToInteger($dataSampah->harga_jual),
                    'id_nasabah' => $pembelian->id_nasabah,
                    'jumlah_jual' => $pembelian->jumlah_beli,
                    'total_harga' => $totalHargaJual * $pembelian->jumlah_beli,
                    'id_pembelian' => $pembelian->id,
                    'created_at' => $pembelian->created_at,
                    'updated_at' => $pembelian->updated_at
                ];

                $percentage = 70;
                $totalSaldoNasabah = ($percentage / 100) * $totalHargaJual;
                $totalSaldoNasabah = $totalSaldoNasabah + $dataNasabah->saldo;

                $dataNasabah->update([
                    'saldo' => $totalSaldoNasabah
                ]);

                HistoryPenjualan::create($formDataPenjualan);
            }
        }
    }

    private function convertRupiahToInteger($rupiah) {
        // Remove the 'Rp ' prefix and any spaces
        $number = str_replace(['Rp ', ' '], '', $rupiah);
        
        // Remove thousand separators (dots)
        $number = str_replace('.', '', $number);
        
        // Convert to integer
        return (int)$number;
    }
}

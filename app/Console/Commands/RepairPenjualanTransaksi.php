<?php

namespace App\Console\Commands;

use App\Models\DataSampah;
use App\Models\HistoryPembelian;
use App\Models\HistoryPenjualan;
use App\Models\Nasabah;
use Illuminate\Console\Command;

class RepairPenjualanTransaksi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repair-transaksi';

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
            $time = time() . random_int(1, 99999);
            $pembelian->unique_key_transaction = $time;
            $pembelian->save();
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

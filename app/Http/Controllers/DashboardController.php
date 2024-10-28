<?php

namespace App\Http\Controllers;

use App\Models\DataSampah;
use App\Models\HistoryPembelian;
use App\Models\HistoryPenjualan;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $data['data_sampah'] = DataSampah::all();
        $data['data_nasabah'] = Nasabah::all();
        $data['total_penjualan'] = $this->integerToRupiah(HistoryPenjualan::sum('total_harga'));
        $data['total_pembelian'] = $this->integerToRupiah(HistoryPembelian::sum('total_harga'));
        $data['penjualan'] = HistoryPenjualan::all()->count();
        $data['pembelian'] = HistoryPembelian::all()->count();
        return view('dashboard.index',$data);
    }

    private function integerToRupiah($integer) {
        return 'Rp ' . number_format($integer, 0, ',', '.');
    }
}

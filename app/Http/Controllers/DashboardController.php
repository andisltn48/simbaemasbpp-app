<?php

namespace App\Http\Controllers;

use App\Models\DataSampah;
use App\Models\HistoryPembelian;
use App\Models\HistoryPenjualan;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        if (in_array(Auth::user()->role, ['admin', 'master_admin'])) {
            $data['data_sampah'] = DataSampah::all();
            $data['data_nasabah'] = Nasabah::all();
            $data['total_penjualan'] = $this->integerToRupiah(HistoryPenjualan::sum('total_harga'));
            $data['total_pembelian'] = $this->integerToRupiah(HistoryPembelian::sum('total_harga'));
            $data['penjualan'] = HistoryPenjualan::all()->count();
            $data['pembelian'] = HistoryPembelian::all()->count();
            return view('dashboard.index',$data);
        } else {
            $dataNasabah = Nasabah::where('user_id', Auth::user()->id)->first();
            $data['saldo'] = $this->integerToRupiah($dataNasabah->saldo);
            $data['histories_pembelian'] = HistoryPembelian::where('id_nasabah', $dataNasabah->id)->get()->toArray();
            $data['histories_penjualan'] = HistoryPenjualan::where('id_nasabah', $dataNasabah->id)->get()->toArray();


            return view('dashboard.index-nasabah', $data);
        }
    }

    private function integerToRupiah($integer) {
        return 'Rp ' . number_format($integer, 0, ',', '.');
    }
}

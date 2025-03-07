<?php

namespace App\Http\Controllers;

use App\Exports\DataSampahExport;
use App\Exports\TransaksiExport;
use App\Models\DataSampah;
use App\Models\HistoryPembelian;
use App\Models\HistoryPenjualan;
use App\Models\Nasabah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DataSampahController extends Controller
{
    public function index() {
        $data['data_sampah'] = DataSampah::all();

        return view('data-sampah.index', $data);
    }

    public function store(Request $request) {
        
        $formData = [
            'nama_sampah' => $request->nama_sampah,
            'satuan' => $request->satuan,
            'harga_beli' => $this->convertRupiahToInteger($request->harga_beli),
            'harga_jual' => $this->convertRupiahToInteger($request->harga_jual),
        ];
        DataSampah::create($formData);

        return redirect()->route('data-sampah.index');
    }

    public function update(Request $request, $id) {
        
        $dataSampah = DataSampah::find($id);
        $formData = [
            'nama_sampah' => $request->nama_sampah,
            'satuan' => $request->satuan,
            'harga_beli' => $this->convertRupiahToInteger($request->harga_beli),
            'harga_jual' => $this->convertRupiahToInteger($request->harga_jual),
        ];
        $dataSampah->update($formData);

        return redirect()->route('data-sampah.index');
    }

    public function destroy($id) {
        DataSampah::find($id)->delete();
        return redirect()->route('data-sampah.index');
    }

    public function detailJson(Request $request)
    {
        $data['sampah'] = DataSampah::find($request->id);

        return response()->json($data, 200);
    }

    public function submitPembelian(Request $request) {
        $requestForm = $request->all();
        
        $tanggal = strtotime($requestForm['tanggal']);
        $tanggal = date("Y-m-d", $tanggal);
        
        $dataNasabah = Nasabah::find($requestForm['id_nasabah']);
        $uniqueKey = time() . random_int(1, 99999);
        foreach ($requestForm['id_sampah'] as $key => $idSampah) {
            $dataSampah = DataSampah::find($idSampah);
            $jumlah = floatval($requestForm['jumlah'][$key]);
            $totalHargaBeli = $jumlah * $this->convertRupiahToInteger($dataSampah->harga_beli);
            $formData = [
                'id_sampah' => $idSampah,
                'nama_sampah' => $dataSampah->nama_sampah,
                'harga' => $this->convertRupiahToInteger($dataSampah->harga_beli),
                'jumlah_beli' => $jumlah,
                'id_nasabah' => $dataNasabah->id,
                'total_harga' => $this->convertRupiahToInteger($totalHargaBeli),
                'unique_key_transaction' => $uniqueKey,
                'created_at' => $tanggal,
                'updated_at' => $tanggal
            ];
            
            $pembelian = HistoryPembelian::create($formData);
            $totalHargaJual = $jumlah * $this->convertRupiahToInteger($dataSampah->harga_jual);
            $formDataPenjualan = [
                'id_sampah' => $idSampah,
                'nama_sampah' => $dataSampah->nama_sampah,
                'harga' => $this->convertRupiahToInteger($dataSampah->harga_jual),
                'id_nasabah' => $dataNasabah->id,
                'jumlah_jual' => $jumlah,
                'total_harga' => $totalHargaJual,
                'id_pembelian' => $pembelian->id,
                'created_at' => $tanggal,
                'updated_at' => $tanggal
            ];
    
            $percentage = 70;
            $totalSaldoNasabah = ($percentage / 100) * $totalHargaJual;
            $totalSaldoNasabah = $totalSaldoNasabah + $dataNasabah->saldo;
    
            $dataNasabah->update([
                'saldo' => $totalSaldoNasabah
            ]);
    
            HistoryPenjualan::create($formDataPenjualan);
        }
        

        return redirect()->route('dashboard.index');
    }


    public function indexHistoryTransaksi(Request $request) {
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['alamat'] = $request->input('alamat');

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        $histories = HistoryPembelian::all()->sortByDesc('created_at');
        $formData = [];
        if ($request->start) {    
            $histories = HistoryPembelian::whereBetween('created_at', [$startDate, $endDate])->get()->sortByDesc('created_at');
        }

        foreach ($histories as $key => $value) {
            if (isset($formData[$value->unique_key_transaction])) continue;
            $history = $value->toArray();
            $nasabah = Nasabah::find($history['id_nasabah']);

            if (Auth::user()->role == 'admin' && Auth::user()->alamat != $nasabah->alamat) {
                continue;
            }
            if ($request->alamat && $request->alamat != '' && $nasabah->alamat != $request->alamat) {
                continue;
            }
            $history['nama_nasabah'] = $nasabah->nama;
            $history['alamat'] = $nasabah->alamat;
            $history['unique_key'] = $value->unique_key_transaction;
            $formData[$value->unique_key_transaction] = $history;
        }
        $data['histories'] = $formData;
        $data['allAlamat'] = $this->getAllAlamat();
        $data['role'] = Auth::user()->role;
        return view('history-transaksi.index', $data);
    }
    
    public function submitPenjualan(Request $request) {
        $dataSampah = DataSampah::find($request->id_sampah);
        $formData = [
            'id_sampah' => $request->id_sampah,
            'nama_sampah' => $dataSampah->nama_sampah,
            'harga' => $this->convertRupiahToInteger($dataSampah->harga_jual),
            'id_nasabah' => $request->id_nasabah,
            'jumlah_jual' => $request->jumlah_jual,
            'total_harga' => $this->convertRupiahToInteger($request->total_harga),
        ];

        HistoryPenjualan::create($formData);

        return redirect()->route('dashboard.index');
    }

    public function indexHistorySampah(Request $request) {
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate)->endOfDay();

        $formData = [];
        $dataSampah = DataSampah::all();
        foreach ($dataSampah as $key => $sampah) {
            $totalJumlahBeli = HistoryPembelian::where('id_sampah', $sampah->id)->sum('jumlah_beli');
            $totalJumlahJual = HistoryPenjualan::where('id_sampah', $sampah->id)->sum('jumlah_jual');
            $totalHarga = HistoryPembelian::where('id_sampah', $sampah->id)->sum('total_harga');
            $totalHargaJual = HistoryPenjualan::where('id_sampah', $sampah->id)->sum('total_harga');
            $totalLaba = $totalHargaJual - $totalHarga;
            if ($request->start) {    
                $totalJumlahBeli = HistoryPembelian::whereBetween('created_at', [$startDate, $endDate])->where('id_sampah', $sampah->id)->sum('jumlah_beli');
                $totalJumlahJual = HistoryPenjualan::whereBetween('created_at', [$startDate, $endDate])->where('id_sampah', $sampah->id)->sum('jumlah_jual');
                $totalHarga = HistoryPembelian::whereBetween('created_at', [$startDate, $endDate])->where('id_sampah', $sampah->id)->sum('total_harga');
                $totalHargaJual = HistoryPenjualan::whereBetween('created_at', [$startDate, $endDate])->where('id_sampah', $sampah->id)->sum('total_harga');
                $totalLaba = $totalHargaJual - $totalHarga;
            }
            
            $formData[$sampah->nama_sampah] = [
                'jumlah_beli' => $totalJumlahBeli,
                'jumlah_jual' => $totalJumlahJual,
                'total_harga_beli' => $totalHarga,
                'total_harga_jual' => $totalHargaJual,
                'laba' => $totalLaba
            ];
        }
        
        $data['histories'] = $formData;
        
        return view('history-sampah.index', $data);
    }

    private function convertRupiahToInteger($rupiah) {
        // Remove the 'Rp ' prefix and any spaces
        $number = str_replace(['Rp ', ' '], '', $rupiah);
        
        // Remove thousand separators (dots)
        $number = str_replace('.', '', $number);
        
        // Convert to integer
        return (int)$number;
    }

    public function pdfHistoryPemasukan(Request $request) {
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $alamat = $request->input('alamat');

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['alamat'] = $alamat;

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        $histories = HistoryPembelian::all()->sortByDesc('created_at');
        $formData = [];
        $tanggalExport = $request->input('start').' s/d '.$request->input('end');
        if ($request->start) {    
            $histories = HistoryPembelian::whereBetween('created_at', [$startDate, $endDate])->get()->sortByDesc('created_at');
        } else {
            $tanggalExport = 'Semua';
        }
        $persentaseNasabah = 70;
        $persentasePengurus1 = 10;
        $persentasePengurus2 = 20;

        foreach ($histories as $key => $value) {
            $history = $value->toArray();
            $nasabah = Nasabah::find($history['id_nasabah']);
            $history['nama_nasabah'] = $nasabah->nama; 
            
            if ($request->alamat && $request->alamat != '' && $nasabah->alamat != $request->alamat) {
                continue;
            }
            $penjualan = HistoryPenjualan::where('id_pembelian', $history['id'])->first();
            $history['total_harga_jual'] = $penjualan ? $penjualan->total_harga : 0;
            $history['jumlah_jual'] = $penjualan ? $penjualan->jumlah_jual : 0;
            $history['harga_jual'] = $penjualan ? $penjualan->harga : 0;
            $history['laba'] = $penjualan ? $history['total_harga_jual'] - $history['total_harga'] : 0;
            $history['pendapatan_nasabah'] = ($persentaseNasabah / 100) * $history['total_harga_jual'];
            $history['pendapatan_pengurus1'] = ($persentasePengurus1 / 100) * $history['total_harga_jual'];
            $history['pendapatan_pengurus2'] = ($persentasePengurus2 / 100) * $history['total_harga_jual'];
            $formData[$value->unique_key_transaction][] = $history;
        }
        $data['histories'] = $formData;
        $data['title'] = 'History Transaksi';
        $data['fileName'] = 'History Transaksi.xlsx';
        $data['path'] = 'reports.transaksi';
        $data['tanggal'] = $tanggalExport;

        // $pdf = $this->exportPDF($data);
        
        // return $pdf->download($data['fileName']);
        return Excel::download(new TransaksiExport($data), $data['fileName']);
    }

    public function pdfHistoryPengeluaran(Request $request) {
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        $formData = [];
        $tanggalExport = $request->input('start').' s/d '.$request->input('end');
        $formData = [];
        $dataSampah = DataSampah::all();
        foreach ($dataSampah as $key => $sampah) {
            $totalJumlahBeli = HistoryPembelian::where('id_sampah', $sampah->id)->sum('jumlah_beli');
            $totalJumlahJual = HistoryPenjualan::where('id_sampah', $sampah->id)->sum('jumlah_jual');
            $totalHarga = HistoryPembelian::where('id_sampah', $sampah->id)->sum('total_harga');
            $totalHargaJual = HistoryPenjualan::where('id_sampah', $sampah->id)->sum('total_harga');
            $totalLaba = $totalHargaJual - $totalHarga;
            if ($request->start) {    
                $totalJumlahBeli = HistoryPembelian::whereBetween('created_at', [$startDate, $endDate])->where('id_sampah', $sampah->id)->sum('jumlah_beli');
                $totalJumlahJual = HistoryPenjualan::whereBetween('created_at', [$startDate, $endDate])->where('id_sampah', $sampah->id)->sum('jumlah_jual');
                $totalHarga = HistoryPembelian::whereBetween('created_at', [$startDate, $endDate])->where('id_sampah', $sampah->id)->sum('total_harga');
                $totalHargaJual = HistoryPenjualan::whereBetween('created_at', [$startDate, $endDate])->where('id_sampah', $sampah->id)->sum('total_harga');
                $totalLaba = $totalHargaJual - $totalHarga;
            }
            
            $formData[$sampah->nama_sampah] = [
                'jumlah_beli' => $totalJumlahBeli,
                'jumlah_jual' => $totalJumlahJual,
                'total_harga_beli' => $totalHarga,
                'total_harga_jual' => $totalHargaJual,
                'laba' => $totalLaba
            ];
        }
        
        $data['histories'] = $formData;
        $data['title'] = 'History Data Sampah';
        $data['fileName'] = 'History Data Sampah.xlsx';
        $data['path'] = 'reports.data-sampah';
        $data['tanggal'] = $tanggalExport;

        // $pdf = $this->exportPDF($data);
        
        return Excel::download(new DataSampahExport($data), $data['fileName']);
    }

    public function exportPDF($data)
    {
        // Load the view and pass the data to it
        $pdf = Pdf::loadView($data['path'], $data)->setPaper('a3', 'landscape');;
        
        // Return the PDF as a download
        return $pdf;
    }

    public function detailJsonHistory(Request $request)
    {
        $histories = HistoryPembelian::where('unique_key_transaction', $request->id)->get();
        
        $persentaseNasabah = 70;
        $persentasePengurus1 = 10;
        $persentasePengurus2 = 20;
        
        foreach ($histories as $key => $value) {
            $history = $value->toArray();
            $history['nama_nasabah'] = Nasabah::find($history['id_nasabah'])->nama;
            $penjualan = HistoryPenjualan::where('id_pembelian', $history['id'])->first();
            $history['total_harga_jual'] = $penjualan ? $penjualan->total_harga : 0;
            $history['jumlah_jual'] = $penjualan ? $penjualan->jumlah_jual : 0;
            $history['harga_jual'] = $penjualan ? $penjualan->harga : 0;
            $history['laba'] = $penjualan ? $history['total_harga_jual'] - $history['total_harga'] : 0;
            $history['pendapatan_nasabah'] = ($persentaseNasabah / 100) * $history['total_harga_jual'];
            $history['pendapatan_pengurus1'] = ($persentasePengurus1 / 100) * $history['total_harga_jual'];
            $history['pendapatan_pengurus2'] = ($persentasePengurus2 / 100) * $history['total_harga_jual'];
            $formData[] = $history;
        }
        $data['histories'] = $formData;

        return response()->json($data, 200);
    }

    public function destroyHistory($uniqueKey)
    {
        $histories = HistoryPembelian::where('unique_key_transaction', $uniqueKey)->get();
        
        foreach ($histories as $key => $value) {
            $value->delete();
            $history = $value->toArray();
            $penjualan = HistoryPenjualan::where('id_pembelian', $history['id'])->first();
            $penjualan->delete();
        }

        return redirect()->back();

    }

    private function getAllAlamat() {
        $nasabah = Nasabah::all();
        $arrayAlamat = [];
        foreach ($nasabah as $key => $value) {
            if (!in_array($value->alamat, $arrayAlamat)) {
                $arrayAlamat[] = $value->alamat;
            }
        }
        return $arrayAlamat;
    }
}

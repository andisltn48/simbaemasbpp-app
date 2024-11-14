<?php

namespace App\Http\Controllers;

use App\Models\DataSampah;
use App\Models\HistoryPembelian;
use App\Models\HistoryPenjualan;
use App\Models\Nasabah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $dataSampah = DataSampah::find($request->id_sampah);
        $formData = [
            'id_sampah' => $request->id_sampah,
            'nama_sampah' => $dataSampah->nama_sampah,
            'harga' => $this->convertRupiahToInteger($dataSampah->harga_beli),
            'jumlah_beli' => $request->jumlah_beli,
            'id_nasabah' => $request->id_nasabah,
            'total_harga' => $this->convertRupiahToInteger($request->total_harga),
        ];

        $dataNasabah = Nasabah::find($request->id_nasabah);
        $totalSaldoNasabah = $dataNasabah->saldo + $this->convertRupiahToInteger($request->total_harga);

        $dataNasabah->update([
            'saldo' => $totalSaldoNasabah
        ]);

        HistoryPembelian::create($formData);

        return redirect()->route('dashboard.index');
    }


    public function indexHistoryPemasukan(Request $request) {
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        $histories = HistoryPembelian::all()->sortByDesc('created_at');
        $formData = [];
        if ($request->start) {    
            $histories = HistoryPembelian::whereBetween('created_at', [$startDate, $endDate])->get()->sortByDesc('created_at');
        }
        
        foreach ($histories as $key => $value) {
            $history = $value->toArray();
            $history['nama_nasabah'] = Nasabah::find($history['id_nasabah'])->nama;

            $formData[] = $history;
        }
        $data['histories'] = $formData;
        return view('history-pemasukan.index', $data);
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

    public function indexHistoryPengeluaran(Request $request) {
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate)->endOfDay();
        $histories = HistoryPenjualan::all()->sortByDesc('created_at');
        $formData = [];
        if ($request->start) {    
            $histories = HistoryPenjualan::whereBetween('created_at', [$startDate, $endDate])->get()->sortByDesc('created_at');
        }
        foreach ($histories as $key => $value) {
            $history = $value->toArray();
            $history['nama_nasabah'] = Nasabah::find($history['id_nasabah'])->nama;

            $formData[] = $history;
        }
        $data['histories'] = $formData;
        
        return view('history-pengeluaran.index', $data);
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

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

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
        
        foreach ($histories as $key => $value) {
            $history = $value->toArray();
            $history['nama_nasabah'] = Nasabah::find($history['id_nasabah'])->nama;

            $formData[] = $history;
        }
        $data['histories'] = $formData;
        $data['title'] = 'History Pemasukan';
        $data['fileName'] = 'History Pemasukan.pdf';
        $data['path'] = 'reports.pemasukan';
        $data['tanggal'] = $tanggalExport;

        $pdf = $this->exportPDF($data);
        
        return $pdf->download($data['fileName']);
    }

    public function pdfHistoryPengeluaran(Request $request) {
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        $histories = HistoryPenjualan::all()->sortByDesc('created_at');
        $formData = [];
        $tanggalExport = $request->input('start').' s/d '.$request->input('end');
        if ($request->start) {    
            $histories = HistoryPenjualan::whereBetween('created_at', [$startDate, $endDate])->get()->sortByDesc('created_at');
        } else {
            $tanggalExport = 'Semua';
        }
        
        foreach ($histories as $key => $value) {
            $history = $value->toArray();
            $history['nama_nasabah'] = Nasabah::find($history['id_nasabah'])->nama;

            $formData[] = $history;
        }
        $data['histories'] = $formData;
        $data['title'] = 'History Penjualan';
        $data['fileName'] = 'History Penjualan.pdf';
        $data['path'] = 'reports.pengeluaran';
        $data['tanggal'] = $tanggalExport;

        $pdf = $this->exportPDF($data);
        
        return $pdf->download($data['fileName']);
    }

    public function exportPDF($data)
    {
        // Load the view and pass the data to it
        $pdf = Pdf::loadView($data['path'], $data)->setPaper('a4', 'landscape');;
        
        // Return the PDF as a download
        return $pdf;
    }
}

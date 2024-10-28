<?php

namespace App\Http\Controllers;

use App\Models\DataSampah;
use App\Models\HistoryPembelian;
use App\Models\HistoryPenjualan;
use App\Models\Nasabah;
use Illuminate\Http\Request;

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
            'total_harga' => $this->convertRupiahToInteger($request->total_harga),
        ];

        HistoryPembelian::create($formData);

        return redirect()->route('dashboard.index');
    }

    public function indexHistoryPemasukan() {
        $data['histories'] = HistoryPembelian::all()->sortByDesc('created_at');

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

    public function indexHistoryPengeluaran() {
        $histories = HistoryPenjualan::all()->sortByDesc('created_at');
        $formData = [];
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
}

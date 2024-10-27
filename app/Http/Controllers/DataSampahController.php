<?php

namespace App\Http\Controllers;

use App\Models\DataSampah;
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

    private function convertRupiahToInteger($rupiah) {
        // Remove the 'Rp ' prefix and any spaces
        $number = str_replace(['Rp ', ' '], '', $rupiah);
        
        // Remove thousand separators (dots)
        $number = str_replace('.', '', $number);
        
        // Convert to integer
        return (int)$number;
    }
}

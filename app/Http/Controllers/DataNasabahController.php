<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Illuminate\Http\Request;

class DataNasabahController extends Controller
{
    public function index() {
        $data['data_nasabah'] = Nasabah::all();

        return view('nasabah.index', $data);
    }

    public function store(Request $request) {
        
        $formData = [
            'nama' => $request->nama,
            'alamat' => $request->alamat
        ];
        Nasabah::create($formData);

        return redirect()->route('data-nasabah.index');
    }

    public function update(Request $request, $id) {
        
        $dataNasabah = Nasabah::find($id);
        $formData = [
            'nama' => $request->nama,
            'alamat' => $request->alamat
        ];
        $dataNasabah->update($formData);

        return redirect()->route('data-nasabah.index');
    }

    public function destroy($id) {
        Nasabah::find($id)->delete();
        return redirect()->route('data-nasabah.index');
    }

    public function detailJson(Request $request)
    {
        $data['nasabah'] = Nasabah::find($request->id);

        return response()->json($data, 200);
    }
}

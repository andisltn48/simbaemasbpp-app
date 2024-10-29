<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Http\Request;

class DataNasabahController extends Controller
{
    public function index() {
        $data['data_nasabah'] = Nasabah::all();

        return view('nasabah.index', $data);
    }

    public function store(Request $request) {
        $user = User::create([
           'name' => $request->nama,
           'email' => $request->email,
           'password' => bcrypt($request->password),
           'role' => 'nasabah'
        ]);
        $formData = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'saldo' => $this->convertRupiahToInteger($request->saldo),
            'user_id' => $user->id
            
        ];
        Nasabah::create($formData);

        return redirect()->route('data-nasabah.index');
    }

    public function update(Request $request, $id) {
        
        $dataNasabah = Nasabah::find($id);
        $user = User::find($dataNasabah->user_id);

        $password = $user->password;
        if ($request->password) {
            $password = bcrypt($request->password);
        }
        $user->update([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => $password,
        ]);
        $formData = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'saldo' => $this->convertRupiahToInteger($request->saldo),
        ];
        $dataNasabah->update($formData);

        return redirect()->route('data-nasabah.index');
    }

    public function destroy($id) {
        $dataNasabah = Nasabah::find($id);
        User::find($dataNasabah->user_id)->delete();
        $dataNasabah->delete();
        return redirect()->route('data-nasabah.index');
    }

    public function detailJson(Request $request)
    {
        $data['nasabah'] = Nasabah::find($request->id);
        $data['user'] = User::find($data['nasabah']->user_id);

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

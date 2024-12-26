<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DataAdminController extends Controller
{
    public function index()
    {
        $data['data_admin'] = User::where('role', 'admin')->get();
        return view('manajemen-admin.index', $data);
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin',
            'alamat' => $request->alamat
        ]);
        return redirect()->route('data-admin.index');
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $password = $user->password;
        if ($request->password) {
            $password = bcrypt($request->password);
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'alamat' => $request->alamat
        ]);
        return redirect()->route('data-admin.index');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('data-admin.index');
    }

    public function detailJson(Request $request)
    {
        $data['user'] = User::find($request->id);
        return response()->json($data, 200);
    }
}

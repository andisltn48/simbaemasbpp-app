<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indexPembelian() {
        return view('dashboard.index-pembelian');
    }
}

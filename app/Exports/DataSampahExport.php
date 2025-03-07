<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DataSampahExport implements FromView
{
    private array $data;
    public function __construct(array $data) {
        $this->data = $data;
    }
    public function view(): View
    {
        return view('reports.data-sampah', $this->data);
    }
}

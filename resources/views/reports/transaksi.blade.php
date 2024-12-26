<div class="table-responsive">
  
  <table class="table table-bordered table-striped" id="dataTable">
    <thead class="thead-dark">
      <tr>
        <td colspan="14">
          <b>Laporan Pemasukan - ({{$tanggal}})</b>
        </td>
      </tr>
      <tr>
        <th>No</th>
        <th>Nama Nasabah</th>
        <th>Nama Sampah</th>
        <th>Harga Beli</th>
        <th>Jumlah Beli</th>
        <th>Total Harga Beli</th>
        <th>Harga Jual</th>
        <th>Jumlah Jual</th>
        <th>Total Harga Jual</th>
        <th>Laba</th>
        <th>Pendapatan Nasabah</th>
        <th>Pendapatan Pengurus 1</th>
        @if (Auth::user()->role == 'master_admin')
            
        <th>Pendapatan Pengurus 2</th>
        @endif
        <th>Tanggal Transaksi</th>
      </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    <tbody>
      @foreach ($histories as $item)
        @foreach ($item as $key => $history)
        @php
            $tanggal = new DateTime($history['created_at']);
            $tanggal = $tanggal->format('Y-m-d');
        @endphp
        <tr>
            @if ($key == 0)
            <td rowspan="{{count($item)}}">{{$no++}}</td>
            <td rowspan="{{count($item)}}">{{$history['nama_nasabah']}}</td>
            @endif
            <td>{{$history['nama_sampah']}}</td>
            <td>{{'Rp ' . number_format($history['harga'], 0, ',', '.')}}</td>
            <td>{{$history['jumlah_beli']}}</td>
            <td>{{'Rp ' . number_format($history['total_harga'], 0, ',', '.')}}</td>
            <td>{{'Rp ' . number_format($history['harga_jual'], 0, ',', '.')}}</td>
            <td>{{$history['jumlah_jual']}}</td>
            <td>{{'Rp ' . number_format($history['total_harga_jual'], 0, ',', '.')}}</td>
            <td>{{'Rp ' . number_format($history['laba'], 0, ',', '.')}}</td>
            <td>{{'Rp ' . number_format($history['pendapatan_nasabah'], 0, ',', '.')}}</td>
            <td>{{'Rp ' . number_format($history['pendapatan_pengurus1'], 0, ',', '.')}}</td>
            @if (Auth::user()->role == 'master_admin')
            
            <td>{{'Rp ' . number_format($history['pendapatan_pengurus2'], 0, ',', '.')}}</td>
            @endif
            <td>{{$tanggal}}</td>
        </tr>
        @endforeach
      @endforeach
    </tbody>
  </table>
</div>

<!-- Additional Custom Styling -->
<style>
  /* Ensure the table is properly spaced and aligned */
  #dataTable {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Arial', sans-serif;
  }

  /* Table header styling */
  #dataTable thead {
    background-color: #343a40;
    color: white;
  }

  #dataTable th {
    text-align: center;
    padding: 10px 15px;
  }

  /* Table body cell styling */
  #dataTable td {
    text-align: center;
    padding: 10px 15px;
    vertical-align: middle;
  }

  /* Zebra striping for rows */
  #dataTable tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
  }

  #dataTable tbody tr:nth-child(even) {
    background-color: #ffffff;
  }

  /* Add hover effect to rows */
  #dataTable tbody tr:hover {
    background-color: #f1f1f1;
  }

  /* Add borders to the table and cells */
  #dataTable, #dataTable th, #dataTable td {
    border: 1px solid #ddd;
  }

  /* Make sure the table is responsive on small screens */
  .table-responsive {
    overflow-x: auto;
  }

  .text-center {
    text-align: center;
  }
</style>

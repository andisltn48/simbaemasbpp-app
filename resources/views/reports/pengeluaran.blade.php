<div class="table-responsive">
  <h3 class="text-center">Laporan Pengeluaran - ({{$tanggal}})</h3>
  <table class="table table-bordered table-striped" id="dataTable">
    <thead class="thead-dark">
      <tr>
        <th>No</th>
        <th>Nama Nasabah</th>
        <th>Nama Sampah</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Total</th>
        <th>Tanggal Transaksi</th>
      </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    <tbody>
      @foreach ($histories as $history)
      @php
          $tanggal = new DateTime($history['created_at']);
          $tanggal = $tanggal->format('Y-m-d');
      @endphp
      <tr>
        <td>{{$no++}}</td>
        <td>{{$history['nama_nasabah']}}</td>
        <td>{{$history['nama_sampah']}}</td>
        <td>{{'Rp ' . number_format($history['harga'], 0, ',', '.')}}</td>
        <td>{{$history['jumlah_jual']}}</td>
        <td>{{'Rp ' . number_format($history['total_harga'], 0, ',', '.')}}</td>
        <td>{{$tanggal}}</td>
      </tr>
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

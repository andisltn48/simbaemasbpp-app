<div class="table-responsive">
  <h3 class="text-center">Laporan Pengeluaran - ({{$tanggal}})</h3>
  <table class="table table-bordered table-striped" id="dataTable">
    <thead class="thead-dark">
      <tr>
        <th>No</th>
        <th>Nama Sampah</th>
        <th>Jumlah Beli (Kg)</th>
        <th>Jumlah Jual (Kg)</th>
        <th>Total Harga Beli</th>
        <th>Total Harga Jual</th>
        <th>Total Laba</th>
      </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    <tbody>
      @foreach ($histories as $key => $history)
      
      <tr>
        <td>{{$no++}}</td>
        <td>{{$key}}</td>
        <td>{{$history['jumlah_beli']}}</td>
        <td>{{$history['jumlah_jual']}}</td>
        <td>{{'Rp ' . number_format($history['total_harga_beli'], 0, ',', '.')}}</td>
        <td>{{'Rp ' . number_format($history['total_harga_jual'], 0, ',', '.')}}</td>
        <td>{{'Rp ' . number_format($history['laba'], 0, ',', '.')}}</td>
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

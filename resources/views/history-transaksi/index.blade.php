<x-app-layout title="History Transaksi">
    <style>
        .btn-create {
            width: 10em;
        }

        .center-btn {
            text-align: center;
        }
    </style>
    <section class="section">
        <div class="section-header">
          <h1>History Transaksi</h1>
          <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
            {{-- <div class="breadcrumb-item"><a href="#">Components</a></div> --}}
            <div class="breadcrumb-item">History Transaksi</div>
          </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body p-3">
                  <div class="form-group">
                    <label>Filter Tanggal</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-calendar"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control daterange-pemasukan">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <a href="{{route('data-sampah.index-pemasukan')}}"><i class="fas fa-undo"></i></a>
                        </div>
                      </div>
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <a href="{{route('data-sampah.pdf-pemasukan')}}?start={{$startDate}}&end={{$endDate}}">Export PDF</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table " id="dataTable">
                      <thead>
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
                            <th>Pendapatan Pengurus 2</th>
                            <th>Tanggal Transaksi</th>
                            {{-- <th>Action</th> --}}
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
                            <td>{{$history['jumlah_beli']}}</td>
                            <td>{{'Rp ' . number_format($history['total_harga'], 0, ',', '.')}}</td>
                            <td>{{'Rp ' . number_format($history['harga_jual'], 0, ',', '.')}}</td>
                            <td>{{$history['jumlah_jual']}}</td>
                            <td>{{'Rp ' . number_format($history['total_harga_jual'], 0, ',', '.')}}</td>
                            <td>{{'Rp ' . number_format($history['laba'], 0, ',', '.')}}</td>
                            <td>{{'Rp ' . number_format($history['pendapatan_nasabah'], 0, ',', '.')}}</td>
                            <td>{{'Rp ' . number_format($history['pendapatan_pengurus1'], 0, ',', '.')}}</td>
                            <td>{{'Rp ' . number_format($history['pendapatan_pengurus2'], 0, ',', '.')}}</td>
                            <td>{{$tanggal}}</td>
                            {{-- <td class="">
                                <a 
                                data-toggle="modal" 
                                data-target="#editModal" 
                                data-id="{{ $nasabah->id }}"
                                data-link="{{ route('data-nasabah.update', $nasabah->id) }}"
                                class="btn-edit col">
                                <i class="fas fa-pen" style="color: #FFD43B;"></i>
                                </a>
                                <a href="" class="col" id="btn-delete" data-confirm="Hapus data|Data yang sudah dihapus tidak bisa dikembalikan" data-confirm-yes="window.location.href='{{ route('data-nasabah.destroy', $nasabah->id) }}'">
                                    <i class="fas fa-trash" style="color: red;"></i>
                                </a>
                            </td> --}}
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                
            </div>
        </div>
    </section>
    <script>
        new DataTable('#dataTable');
    </script>
    
    <script>
      var picker = $('.daterange-pemasukan').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        drops: 'down',
        opens: 'right'
      });

    // Set the start and end date
      var startDate = "{{$startDate}}";
      var endDate = "{{$endDate}}";
      var rangeDate = startDate + ' - ' + endDate;
      $('.daterange-pemasukan').val(rangeDate);
      $('.daterange-pemasukan').on('apply.daterangepicker', function(ev, picker) {
        // The 'picker' object contains the startDate and endDate
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        

        // Example action: Display selected dates in an alert
        // alert('You selected: ' + startDate + ' to ' + endDate);
        window.location.href = "{{ route('data-sampah.index-pemasukan') }}?start=" + startDate + "&end=" + endDate;
    });
    </script>
</x-app-layout>
<x-app-layout title="History Sampah">
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
          <h1>History Sampah</h1>
          <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
            {{-- <div class="breadcrumb-item"><a href="#">Components</a></div> --}}
            <div class="breadcrumb-item">History Sampah</div>
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
                      <input type="text" class="form-control daterange-pengeluaran">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <a href="{{route('data-sampah.index-pengeluaran')}}"><i class="fas fa-undo"></i></a>
                        </div>
                      </div>
                      
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <a href="{{route('data-sampah.pdf-pengeluaran')}}?start={{$startDate}}&end={{$endDate}}">Export Excel</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table " id="dataTable">
                      <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sampah</th>
                            <th>Jumlah Beli (Kg)</th>
                            <th>Jumlah Jual (Kg)</th>
                            <th>Total Harga Beli</th>
                            <th>Total Harga Jual</th>
                            <th>Total Laba</th>
                            {{-- <th>Action</th> --}}
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
      var picker = $('.daterange-pengeluaran').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        drops: 'down',
        opens: 'right'
      });

    // Set the start and end date
      var startDate = "{{$startDate}}";
      var endDate = "{{$endDate}}";
      var rangeDate = startDate + ' - ' + endDate;
      $('.daterange-pengeluaran').val(rangeDate);
      $('.daterange-pengeluaran').on('apply.daterangepicker', function(ev, picker) {
        // The 'picker' object contains the startDate and endDate
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        
        // Do something with the selected date range
        // Example action: Display selected dates in an alert
        // alert('You selected: ' + startDate + ' to ' + endDate);
        window.location.href = "{{ route('data-sampah.index-pengeluaran') }}?start=" + startDate + "&end=" + endDate;
    });
    </script>
</x-app-layout>
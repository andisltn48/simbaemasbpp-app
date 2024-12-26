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
                          <a href="{{route('data-sampah.pdf-pemasukan')}}?start={{$startDate}}&end={{$endDate}}&alamat={{$alamat}}">Export PDF</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  @if (Auth::user()->role === 'master_admin')
                  <div class="form-group">
                    <label>Filter Alamat</label>
                    <div>
                      <select name="" id="select-alamat" onchange="filterAlamat()">
                        <option value="">Pilih Alamat</option>
                        @foreach ($allAlamat as $selectAlamat)
                            <option value="{{$selectAlamat}}" {{$selectAlamat == $alamat ? 'selected' : ''}}>{{$selectAlamat}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  @endif
                  <div class="table-responsive">
                    <table class="table " id="dataTable">
                      <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Nasabah</th>
                            <th>Alamat</th>
                            <th>Tanggal Transaksi</th>
                            <th>Action</th>
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
                            <td>{{$history['alamat']}}</td>
                            <td>{{$tanggal}}</td>
                            <td class="">
                                <a 
                                data-toggle="modal" 
                                data-target="#detailModal" 
                                data-id="{{ $history['unique_key']}}"
                                data-link="{{ route('data-sampah.detail-history', $history['unique_key']) }}"
                                class="btn-detail col">
                                <i class="fas fa-eye" style="color: #FFD43B;"></i>
                                </a>
                                <a href="" class="col" id="btn-delete" data-confirm="Hapus data|Data yang sudah dihapus tidak bisa dikembalikan" data-confirm-yes="window.location.href='{{ route('data-sampah.destroy-history', $history['unique_key']) }}'">
                                    <i class="fas fa-trash" style="color: red;"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                
            </div>
        </div>
    </section>
    
    <div class="modal fade" tabindex="-1" role="dialog" id="detailModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail Transaksi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post" id="form-detail">
              @csrf
              <div class="modal-body">
                <div class="table-responsive">
                  <table class="table " id="dataTableDetail">
                    <thead>
                      <tr>
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
                          {{-- <th>Action</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
              {{-- <div class="modal-footer bg-whitesmoke br">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
              </div> --}}
          </form>
        </div>
      </div>
    </div>
    <script>
        new DataTable('#dataTable');
        //script handle edit
        $('table tbody').on('click', '.btn-detail', function() {
            var id = $(this).data('id');
            
            $.ajax({
                method: "GET",
                data: {
                    id: id
                },
                url: $(this).data('link'),
            }).done(function(response) {
                $('#dataTableDetail tbody').empty();
                var role = "{{ Auth::user()->role }}";
                response.histories.forEach(element => {
                  var newRow = '<tr>' +
                                  '<td>' + element['nama_sampah'] + '</td>' +
                                  '<td>' + formatToIDR(element['harga']) + '</td>' +
                                  '<td>' + element['jumlah_beli'] + '</td>' +
                                  '<td>' + formatToIDR(element['total_harga']) + '</td>' +
                                  '<td>' + formatToIDR(element['harga_jual']) + '</td>' +
                                  '<td>' + element['jumlah_jual'] + '</td>' +
                                  '<td>' + formatToIDR(element['total_harga_jual']) + '</td>' +
                                  '<td>' + formatToIDR(element['laba']) + '</td>' +
                                  '<td>' + formatToIDR(element['pendapatan_nasabah']) + '</td>' +
                                  '<td>' + formatToIDR(element['pendapatan_pengurus1']) + '</td>';
                  if (role == 'master_admin') {
                    newRow = newRow + '<td>' + formatToIDR(element['pendapatan_pengurus2']) + '</td>' +    
                    '</tr>';
                  } else {
                    newRow = newRow + '</tr>';
                  }
                  $('#dataTableDetail tbody').append(newRow);
                });
                table = new DataTable('#dataTableDetail');
                
            });

        });
        
        function formatToIDR(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);
        }
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
      var alamat = "{{$alamat}}";
      var rangeDate = startDate + ' - ' + endDate;
      $('.daterange-pemasukan').val(rangeDate);
      $('.daterange-pemasukan').on('apply.daterangepicker', function(ev, picker) {
        // The 'picker' object contains the startDate and endDate
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        

        // Example action: Display selected dates in an alert
        // alert('You selected: ' + startDate + ' to ' + endDate);
        var url = "{{ route('data-sampah.index-pemasukan') }}?start=" + startDate + "&end=" + endDate;
        if (alamat != null) {
          url += "&alamat=" + alamat;
        }
        window.location.href = url;
    });

    function filterAlamat() {
      var startDate = "{{$startDate}}";
      var endDate = "{{$endDate}}";
      var alamat = $('#select-alamat').val();
      var url = "{{ route('data-sampah.index-pemasukan') }}?alamat=" + alamat;
      if (startDate != "") {
        url += "&start=" + startDate + "&end=" + endDate;
      }
      window.location.href = url;
    }
    </script>
</x-app-layout>
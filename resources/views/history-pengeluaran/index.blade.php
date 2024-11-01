<x-app-layout title="History Pengeluaran">
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
          <h1>History Pengeluaran</h1>
          <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
            {{-- <div class="breadcrumb-item"><a href="#">Components</a></div> --}}
            <div class="breadcrumb-item">History Pengeluaran</div>
          </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body p-3">
                  <div class="table-responsive">
                    <table class="table " id="dataTable">
                      <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Nasabah</th>
                            <th>Nama Sampah</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
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
                            <td>{{$history['jumlah_jual']}}</td>
                            <td>{{'Rp ' . number_format($history['total_harga'], 0, ',', '.')}}</td>
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
</x-app-layout>
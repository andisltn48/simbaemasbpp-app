<x-app-layout title="Data Sampah">
    <style>
        .btn-create {
            width: 10em;
        }

        .center-btn {
            text-align: center;
        }

        /* Initially show the div */
        .showonmobile {
            display: none;
        }

        /* Hide div on smaller screens (e.g., 600px or less) */
        @media (max-width: 600px) {
            .showonweb {
                display: none;
            }
            .showonmobile {
                display: block;
                overflow-x: hidden;  /* Hide horizontal scroll */
                width: 100%; /* Ensure the container takes the full width */
            }
        }

        /* Show div on larger screens (e.g., more than 600px) */
        @media (min-width: 601px) {
            
            .showonmobile {
                display: none;
            }
        }
    </style>
    <section class="section">
        <div class="section-header">
          <h1>Data Sampah</h1>
          <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
            {{-- <div class="breadcrumb-item"><a href="#">Components</a></div> --}}
            <div class="breadcrumb-item">Data Sampah</div>
          </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="m-3 btn btn-primary btn-create" data-toggle="modal" data-target="#createModal">
                        Tambah
                    </button>
                </div>
                
                <div class="card-body p-3">
                  <div class="table-responsive showonweb">
                    <table class="table" id="dataTable">
                      <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sampah</th>
                            <th>Satuan</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                      @php
                          $no = 1;
                      @endphp
                      <tbody>
                        @foreach ($data_sampah as $sampah)
                          
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$sampah->nama_sampah}}</td>
                            <td>{{$sampah->satuan}}</td>
                            <td>{{'Rp ' . number_format($sampah->harga_beli, 0, ',', '.')}}</td>
                            <td>{{'Rp ' . number_format($sampah->harga_jual, 0, ',', '.')}}</td>
                            <td class="">
                                <a 
                                data-toggle="modal" 
                                data-target="#editModal" 
                                data-id="{{ $sampah->id }}"
                                data-link="{{ route('data-sampah.update', $sampah->id) }}"
                                class="btn-edit col">
                                <i class="fas fa-pen" style="color: #FFD43B;"></i>
                                </a>
                                <a href="" class="col" id="btn-delete" data-confirm="Hapus data|Data yang sudah dihapus tidak bisa dikembalikan" data-confirm-yes="window.location.href='{{ route('data-sampah.destroy', $sampah->id) }}'">
                                    <i class="fas fa-trash" style="color: red;"></i>
                                </a>
                            </td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
                  <div class="table-responsive showonmobile">
                    <table class="table" id="dataTableOnMobile">
                        <thead>
                            <tr>
                                <th>Nama Sampah & Harga Beli</th>
                            </tr>
                        </thead>
                        @php
                            $no = 1;
                        @endphp
                        <tbody>
                            @foreach ($data_sampah as $sampah)
                            
                            <tr>
                                <td>{{$sampah->nama_sampah}} | {{'Rp ' . number_format($sampah->harga_beli, 0, ',', '.')}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                  </div>
                </div>
                
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="createModal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Data Sampah</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{route('data-sampah.store')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Sampah</label>
                        <input type="text" name="nama_sampah" class="form-control" id="name">
                    </div>
                    <div class="form-group">
                        <label for="satuan">Satuan</label>
                        <input type="text" name="satuan" class="form-control" id="satuan">
                    </div>
                    <div class="form-group">
                        <label for="harga_beli">Harga Beli</label>
                        <input type="text" name="harga_beli" class="form-control" id="harga_beli" oninput="onInputChange(event)">
                    </div>
                    <div class="form-group">
                        <label for="harga_jual">Harga Jual</label>
                        <input type="text" name="harga_jual" class="form-control" id="harga_jual" oninput="onInputChange(event)">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
          </div>
        </div>
    </div>
    
    <div class="modal fade" tabindex="-1" role="dialog" id="editModal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Data Sampah</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post" id="form-edit">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name-edit">Nama Sampah</label>
                        <input type="text" name="nama_sampah" class="form-control" id="name-edit">
                    </div>
                    <div class="form-group">
                        <label for="satuan-edit">Satuan</label>
                        <input type="text" name="satuan" class="form-control" id="satuan-edit">
                    </div>
                    <div class="form-group">
                        <label for="harga_beli-edit">Harga Beli</label>
                        <input type="text" name="harga_beli" class="form-control" id="harga_beli-edit" oninput="onInputChange(event)">
                    </div>
                    <div class="form-group">
                        <label for="harga_jual-edit">Harga Jual</label>
                        <input type="text" name="harga_jual" class="form-control" id="harga_jual-edit" oninput="onInputChange(event)">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
          </div>
        </div>
    </div>
    <script>
        new DataTable('#dataTable');
        new DataTable('#dataTableOnMobile');

        function formatRupiah(angka) {
            let numberString = angka.toString().replace(/[^,\d]/g, '').toString();
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            
            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        function onInputChange(event) {
            const inputField = event.target;
            const formattedValue = formatRupiah(inputField.value);
            inputField.value = formattedValue;
        }

        //script handle edit
        $('table tbody').on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            $(".form-edit").trigger("reset");

            $.ajax({
                method: "GET",
                data: {
                    id: id
                },
                url: "{{ route('data-sampah.detail-json') }}",
            }).done(function(response) {
                $("#name-edit").val(response.sampah.nama_sampah);
                $("#satuan-edit").val(response.sampah.satuan);

                var hargaBeli = formatRupiah(response.sampah.harga_beli);
                $("#harga_beli-edit").val(hargaBeli);

                var hargaJual = formatRupiah(response.sampah.harga_jual);
                $("#harga_jual-edit").val(hargaJual);
            });

            
            $("#form-edit").attr("action", $(this).data("link"));

        });
    </script>
</x-app-layout>
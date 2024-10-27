<x-app-layout title="Nasabah">
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
          <h1>Nasabah</h1>
          <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
            {{-- <div class="breadcrumb-item"><a href="#">Components</a></div> --}}
            <div class="breadcrumb-item">Nasabah</div>
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
                  <div class="table-responsive">
                    <table class="table " id="dataTable">
                      <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Nasabah</th>
                            <th>Alamat</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                      @php
                          $no = 1;
                      @endphp
                      <tbody>
                        @foreach ($data_nasabah as $nasabah)
                          
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$nasabah->nama}}</td>
                            <td>{{$nasabah->alamat}}</td>
                            <td class="">
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
    <div class="modal fade" tabindex="-1" role="dialog" id="createModal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Nasabah</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{route('data-nasabah.store')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Nasabah</label>
                        <input type="text" name="nama" class="form-control" id="name">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" class="form-control" id="alamat">
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
              <h5 class="modal-title">Edit Data Nasabah</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post" id="form-edit">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name-edit">Nama Nasabah</label>
                        <input type="text" name="nama" class="form-control" id="name-edit">
                    </div>
                    <div class="form-group">
                        <label for="alamat-edit">Alamat</label>
                        <input type="text" name="alamat" class="form-control" id="alamat-edit">
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
                url: "{{ route('data-nasabah.detail-json') }}",
            }).done(function(response) {
                $("#name-edit").val(response.nasabah.nama);
                $("#alamat-edit").val(response.nasabah.alamat);
            });

            
            $("#form-edit").attr("action", $(this).data("link"));

        });
    </script>
</x-app-layout>
<x-app-layout title="Dashboard">
    <section class="section">
        <div class="section-header">
          <h1>Dashboard</h1>
        </div>
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-primary">
                <i class="fas fa-shopping-bag"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Pembelian</h4>
                </div>
                <div class="card-body">
                  {{$pembelian}}
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-danger">
                <i class="fas fa-shopping-bag"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Penjualan</h4>
                </div>
                <div class="card-body">
                  {{$penjualan}}
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-warning">
                <i class="fas fa-wallet"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Total Pembelian</h4>
                </div>
                <div class="card-body">
                  {{$total_pembelian}}
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="fas fa-wallet"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Total Penjualan</h4>
                </div>
                <div class="card-body">
                  {{$total_penjualan}}
                </div>
              </div>
            </div>
          </div>                  
        </div>
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="fas fa-shopping-bag"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Total Pembelian (Kg)</h4>
                </div>
                <div class="card-body">
                  {{$pembelian_in_kg}}
                </div>
              </div>
            </div>
          </div>                  
        </div>
        <form method="post" action="{{route('data-sampah.submit-pembelian')}}">
        @csrf
        <div class="row">
            <div class="col">
              <div class="card">
                  <div class="card-header">
                    <h4>Pembelian</h4>
                  </div>
                  <div class="card-body">
                    
                    <p>Tanggal transaksi <b>(Y-m-d)</b> <b><input required name="tanggal" type="text" class="form-control" value="{{date("Y-m-d")}}"></b></p>
                    
                    <div class="form-group">
                      <label>Nama Nasabah</label>
                      <select name="id_nasabah" class="form-control select-nasabah" required>
                        <option value="">Pilih Nasabah</option>
                        @foreach ($data_nasabah as $nasabah)
                            <option value="{{$nasabah->id}}">{{$nasabah->nama}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  
                  <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    
                    <button onclick="addForm()" type="button" class="btn btn-primary">Tambah</button>
                  </div>
              </div>
            </div>
            {{-- <div class="col-12 col-md-6 col-lg-6">
              <div class="card">
                <form method="post" action="{{route('data-sampah.submit-penjualan')}}">
                  @csrf
                  <div class="card-header">
                    <h4>Penjualan</h4>
                  </div>
                  <div class="card-body">
                    
                    <p>Tanggal transaksi <b>({{{date("Y-m-d")}}})</b></p>
                    <div class="form-group">
                      <label>Nama Sampah</label>
                      <select name="id_sampah" class="form-control select-sampah" id="select-sampah-penjualan">
                        <option value="">Pilih Sampah</option>
                        @foreach ($data_sampah as $sampah)
                            <option value="{{$sampah->id}}">{{$sampah->nama_sampah}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Nama Nasabah</label>
                      <select name="id_nasabah" class="form-control select-nasabah">
                        <option value="">Pilih Nasabah</option>
                        @foreach ($data_nasabah as $nasabah)
                            <option value="{{$nasabah->id}}">{{$nasabah->nama}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Harga</label>
                      <input type="text" class="form-control" readonly id="harga-penjualan">
                    </div>
                    <div class="form-group">
                      <label>Satuan</label>
                      <input type="text" class="form-control" readonly id="satuan-penjualan">
                    </div>
                    <div class="form-group">
                      <label>Jumlah Beli</label>
                      <input name="jumlah_jual" class="form-control" type="number" required oninput="onInputChangePenjualan(event)">
                    </div>
                    <div class="form-group">
                      <label>Total Harga</label>
                      <input name="total_harga" class="form-control" type="text" readonly id="total-harga-penjualan">
                    </div>
                  </div>
                  <div class="card-footer text-right">
                    <button class="btn btn-primary">Simpan</button>
                  </div>
                </form>
                </div>
              </div>
            </div> --}}
        </div>
        <div class="col" id="forms-list">
                    
        </div>
      </form>
    </section>
    <script>
        $('.select-sampah').select2({
          placeholder: "--- Pilih Sampah ---",
          selectOnClose: true
        });
        $('.select-nasabah').select2({
          placeholder: "--- Pilih Nasabah ---",
          selectOnClose: true
        });

        $('#select-sampah').change(function(){
            
            var id = $(this).val();

            $.ajax({
                method: "GET",
                data: {
                    id: id
                },
                url: "{{ route('data-sampah.detail-json') }}",
            }).done(function(response) {
                $("#harga").val(formatRupiah(response.sampah.harga_beli));
                $("#harga-penjualan").val(formatRupiah(response.sampah.harga_jual));
                $("#satuan").val(response.sampah.satuan);
            });

        })

        $('#select-sampah-penjualan').change(function(){
            
            var id = $(this).val();

            $.ajax({
                method: "GET",
                data: {
                    id: id
                },
                url: "{{ route('data-sampah.detail-json') }}",
            }).done(function(response) {
                $("#harga-penjualan").val(formatRupiah(response.sampah.harga_jual));
                $("#satuan-penjualan").val(response.sampah.satuan);
            });

        })

        function onInputChange(event, id) {
            
            const inputField = event.target;
            var idSampah = inputField.value;

            $.ajax({
                method: "GET",
                data: {
                    id: idSampah
                },
                url: "{{ route('data-sampah.detail-json') }}",
            }).done(function(response) {
                let classHarga = `.harga-${id}`;
                console.log(classHarga);
                $(`${classHarga}`).val(formatRupiah(response.sampah.harga_beli));
            });
            
        }

        function onInputJumlah(event, id) {
            const inputField = event.target;
            const formattedValue = inputField.value;
            const harga = convertRupiahToInteger($(`.harga-${id}`).val());
            const totalHarga = parseInt(harga * inputField.value);
            $(`.total-harga-${id}`).val(formatRupiah(totalHarga));
            // const hargaJual = convertRupiahToInteger($("#harga-penjualan").val());
            // const totalHargaJual = parseInt(hargaJual * inputField.value);
            
            // $("#total-harga-penjualan").val(formatRupiah(totalHargaJual));
        }
        function onInputChangePenjualan(event) {
            const inputField = event.target;
            const formattedValue = inputField.value;
            const harga = convertRupiahToInteger($("#harga-penjualan").val());
            const totalHarga = harga * inputField.value;
            $("#total-harga-penjualan").val(formatRupiah(totalHarga));
        }

        function convertRupiahToInteger(rupiahString) {
            // Remove "Rp" and any whitespace
            let cleanedString = rupiahString.replace(/Rp\s*|\./g, '').trim();
            
            // Convert to integer
            let amount = parseInt(cleanedString);
            
            return amount;
        }

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
    </script>
    <script>
      let formCount = 0;

      function addForm() {
          formCount++;
          const formContainer = document.createElement('div');
          formContainer.classList.add('form-item');
          formContainer.setAttribute('id', `form-${formCount}`);
          formContainer.setAttribute('class', 'form-row');

          formContainer.innerHTML = `
              <div class="form-group col">
                <label for="inputEmail4">Nama Sampah</label>
                <select name="id_sampah[]" class="form-control select-sampah" id="select-sampah-${formCount}" onchange="onInputChange(event,${formCount})" required>
                  <option value="">Pilih Sampah</option>
                  @foreach ($data_sampah as $sampah)
                      <option value="{{$sampah->id}}">{{$sampah->nama_sampah}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col">
                <label for="inputPassword4">Harga</label>
                <input type="text" class="form-control harga-${formCount}" id="inputPassword4" placeholder="text" readonly>
              </div>
              <div class="form-group col">
                <label for="inputPassword4">Jumlah Beli</label>
                <input type="number" name="jumlah[]"  class="form-control jumlah-beli-${formCount}" oninput="onInputJumlah(event,${formCount})" id="inputPassword4" step="0.01" required>
              </div>
              <div class="form-group col">
                <label for="inputPassword4">Total Harga Beli</label>
                <input type="text" class="form-control total-harga-${formCount}" id="inputPassword4" readonly>
              </div>
              <div class="form-group col">
                <button type="button" onclick="deleteForm(${formCount})"><i class="fas fa-trash" style="color: red;"></i></button>
              </div>
          `;

          document.getElementById('forms-list').appendChild(formContainer);
      }

      function deleteForm(formId) {
          const form = document.getElementById(`form-${formId}`);
          form.remove();
      }
  </script>
</x-app-layout>
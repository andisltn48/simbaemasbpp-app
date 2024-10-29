<x-app-layout title="Dashboard">
    <section class="section">
        <div class="section-header">
          <h1>Dashboard</h1>
        </div>
        <div class="row">
          
          <div class="col">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="fas fa-wallet"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Saldo</h4>
                </div>
                <div class="card-body">
                  {{$saldo}}
                </div>
              </div>
            </div>
          </div>                  
        </div>
        <div class="card">
          <div class="card-header">
            <h4>History</h4>
          </div>
          <div class="card-body">
            <p class="buttons">
              <a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1" id="pembelian">Pembelian</a>
              <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2" id="penjualan">Penjualan</button>
            </p>
            <div class=>
              <div class="col">
                <div class="collapse multi-collapse show" id="multiCollapseExample1">
                  <div class="table-responsive">
                    <table class="table " id="dataTable">
                      <thead>
                        <tr>
                            <th>No</th>
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
                        @foreach ($histories_pembelian as $history)
                        @php
                            $tanggal = new DateTime($history['created_at']);
                            $tanggal = $tanggal->format('Y-m-d');
                        @endphp
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$history['nama_sampah']}}</td>
                            <td>{{'Rp ' . number_format($history['harga'], 0, ',', '.')}}</td>
                            <td>{{$history['jumlah_beli']}}</td>
                            <td>{{'Rp ' . number_format($history['total_harga'], 0, ',', '.')}}</td>
                            <td>{{$tanggal}}</td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="collapse multi-collapse" id="multiCollapseExample2">
                  <div class="table-responsive">
                    <table class="table " id="dataTablePenjualan">
                      <thead>
                        <tr>
                            <th>No</th>
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
                        @foreach ($histories_penjualan as $history)
                        @php
                            $tanggal = new DateTime($history['created_at']);
                            $tanggal = $tanggal->format('Y-m-d');
                        @endphp
                        <tr>
                            <td>{{$no++}}</td>
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
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
    <script>
      
        new DataTable('#dataTable');
        new DataTable('#dataTablePenjualan');
        function onInputChange(event) {
            const inputField = event.target;
            const formattedValue = inputField.value;
            const harga = convertRupiahToInteger($("#harga").val());
            const totalHarga = harga * inputField.value;
            $("#total-harga").val(formatRupiah(totalHarga));
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
            let amount = parseInt(cleanedString, 10);
            
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

        document.getElementById('pembelian').addEventListener('click', function() {
            const div = document.getElementById('multiCollapseExample2');
            div.classList.remove('show'); // Remove the 'highlight' class
        });

        document.getElementById('penjualan').addEventListener('click', function() {
            const div = document.getElementById('multiCollapseExample1');
            div.classList.remove('show'); // Remove the 'highlight' class
        });
    </script>
</x-app-layout>
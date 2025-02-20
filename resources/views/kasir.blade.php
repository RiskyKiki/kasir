<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Penjualan</title>
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('modules/bootstrap/css/bootstrap.min.css') }}">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .custom-bg {
            background-color: #6777ef;
        }
    </style>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="custom-bg p-4">
    <div class="container bg-white p-6 rounded-lg shadow-lg">
        <div class="d-flex align-items-center pt-2 mb-6">
            <h1 class="h4 font-weight-bold text-gray-700 flex-grow-1">Transaksi Penjualan</h1>
            <a href="{{ route('dashboard') }}" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="form-row mb-4">
                    <div class="form-group col-md-6">
                        <label for="pilihPelanggan" class="text-gray-700">Pilih Pelanggan</label>
                        <!-- Opsi default dengan value kosong -->
                        <select id="pilihPelanggan" class="form-control">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach ($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}" data-telepon="{{ $pelanggan->telepon }}"
                                    data-alamat="{{ $pelanggan->alamat }}" data-poin="{{ $pelanggan->poin }}"
                                    data-tipe="{{ $pelanggan->tipe }}">
                                    {{ $pelanggan->nama }} - ({{ $pelanggan->tipe }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="pilihProduk" class="text-gray-700">Pilih Produk</label>
                        <select id="pilihProduk" class="form-control">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($produks as $produk)
                                @if ($produk->stok > 0)
                                    <option value="{{ $produk->id }}" data-harga1="{{ $produk->harga1 }}"
                                        data-harga2="{{ $produk->harga2 }}" data-harga3="{{ $produk->harga3 }}"
                                        data-hpp="{{ $produk->hpp }}" data-stok="{{ $produk->stok }}">
                                        {{ $produk->nama }} - Stok: {{ $produk->stok }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <table class="table table-bordered display table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Produk</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="list_produk">
                        <!-- Produk yang dipilih akan muncul di sini -->
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <div class="form-row mb-4">
                    <div class="form-group col-md-6">
                        <label for="poinDimiliki" class="text-gray-700">Poin yang Dimiliki</label>
                        <input type="text" id="poinDimiliki" class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="poinDidapat" class="text-gray-700">Poin Didapat (2%)</label>
                        <input type="text" id="poinDidapat" class="form-control" readonly>
                    </div>
                </div>
                <div class="bg-light p-4 rounded-lg">
                    <h2 class="h5 font-weight-bold text-gray-700 mb-4">Ringkasan Pembayaran</h2>
                    <div class="form-group mb-4">
                        <label for="subtotalKeseluruhan" class="text-gray-700">Subtotal Keseluruhan</label>
                        <input type="text" id="subtotalKeseluruhan" class="form-control" readonly>
                        <!-- Hidden numeric value -->
                        <input type="hidden" id="subtotalKeseluruhanVal" value="0">
                    </div>

                    <div class="form-row mb-4">
                        <div class="form-group col-md-6">
                            <label for="diskon" class="text-gray-700">Diskon (otomatis)</label>
                            <input type="text" id="diskon" class="form-control" readonly>
                            <input type="hidden" id="diskonVal" value="0">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pajak" class="text-gray-700">Pajak (12%)</label>
                            <input type="text" id="pajak" class="form-control" readonly>
                            <input type="hidden" id="pajakVal" value="0">
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="poinDigunakan" class="text-gray-700">Poin yang Digunakan</label>
                        <input type="number" id="poinDigunakan" class="form-control" value="0"
                            oninput="renderTable()">
                        <small id="poinHelp" class="form-text text-muted">
                            Maksimal poin yang dapat digunakan adalah 50% dari subtotal.
                        </small>
                    </div>
                    <div class="form-group mb-4">
                        <label for="totalAkhir" class="text-gray-700">Total Akhir</label>
                        <input type="text" id="totalAkhir" class="form-control" readonly>
                        <input type="hidden" id="totalAkhirVal" value="0">
                    </div>
                    <div class="form-group mb-4">
                        <label for="pembayaran" class="text-gray-700">Pembayaran</label>
                        <input type="text" id="pembayaran" class="form-control" oninput="hitungKembalian()">
                    </div>
                    <div class="form-group mb-4">
                        <label for="kembalian" class="text-gray-700">Kembalian</label>
                        <input type="text" id="kembalian" class="form-control" readonly>
                    </div>
                    <button id="simpanTransaksiBtn" class="btn btn-primary btn-block"
                        onclick="simpanTransaksi()">Simpan
                        Transaksi</button>
                </div>
            </div>
        </div>

        <!-- Library JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <script>
            let transaksi = [];
            let removedOptions = {};
            // Tambahkan produk ke daftar transaksi
            function tambahProduk() {
                let pelanggan = document.getElementById("pilihPelanggan");
                let selectedPelanggan = pelanggan.options[pelanggan.selectedIndex];
                let tipePelanggan = selectedPelanggan.value ? selectedPelanggan.getAttribute("data-tipe") : null;

                let produk = document.getElementById("pilihProduk");
                let selected = produk.options[produk.selectedIndex];
                if (!selected.value) return;

                let harga = 0;
                if (!tipePelanggan) {
                    harga = parseFloat(selected.getAttribute("data-hpp")) * 1.40;
                } else if (tipePelanggan === "Emas") {
                    harga = parseFloat(selected.getAttribute("data-harga1"));
                } else if (tipePelanggan === "Perak") {
                    harga = parseFloat(selected.getAttribute("data-harga2"));
                } else {
                    harga = parseFloat(selected.getAttribute("data-harga3"));
                }

                let produkData = {
                    produk_id: selected.value,
                    nama: selected.text,
                    harga: harga,
                    jumlah: 1,
                    subtotal: harga,
                    hpp: parseFloat(selected.getAttribute("data-hpp")),
                    harga1: parseFloat(selected.getAttribute("data-harga1")),
                    harga2: parseFloat(selected.getAttribute("data-harga2")),
                    harga3: parseFloat(selected.getAttribute("data-harga3"))
                };

                transaksi.push(produkData);
                removedOptions[selected.value] = selected;
                produk.remove(produk.selectedIndex);
                renderTable();
                produk.selectedIndex = 0;
            }

            // Update informasi pelanggan dan perbarui harga produk jika diperlukan
            function updatePelanggan() {
                let pelanggan = document.getElementById("pilihPelanggan");
                let selected = pelanggan.options[pelanggan.selectedIndex];
                let tipePelanggan = selected.value ? selected.getAttribute("data-tipe") : null;

                document.getElementById("poinDimiliki").value = selected.value ? selected.getAttribute("data-poin") : 0;

                // Rekalkulasi harga produk berdasarkan tipe pelanggan baru menggunakan data yang disimpan di transaksi
                transaksi.forEach((item) => {
                    let newPrice = 0;
                    if (!tipePelanggan) {
                        newPrice = item.hpp * 1.40;
                    } else if (tipePelanggan === "Emas") {
                        newPrice = item.harga1;
                    } else if (tipePelanggan === "Perak") {
                        newPrice = item.harga2;
                    } else {
                        newPrice = item.harga3;
                    }
                    item.harga = newPrice;
                    item.subtotal = newPrice * item.jumlah;
                });

                renderTable();
            }

            // Render ulang tabel produk dan perbarui ringkasan pembayaran
            function renderTable() {
                let tbody = document.getElementById("list_produk");
                tbody.innerHTML = "";

                // Hitung subtotal keseluruhan dari transaksi
                let subtotalKeseluruhan = transaksi.reduce((acc, item) => acc + item.subtotal, 0);

                // Validasi poin yang digunakan (maksimal 50% dari subtotal)
                let poinInput = document.getElementById("poinDigunakan");
                let poinDigunakan = parseFloat(poinInput.value) || 0;
                let maxPoin = subtotalKeseluruhan * 0.5;
                if (poinDigunakan > maxPoin) {
                    poinDigunakan = maxPoin;
                    poinInput.value = poinDigunakan.toFixed(2);
                }

                transaksi.forEach((item, index) => {
                    tbody.innerHTML += `
            <tr>
              <td>${item.nama}</td>
              <td>${formatRupiah(item.harga)}</td>
              <td>
                <input type="number" value="${item.jumlah}" min="1" onchange="ubahJumlah(${index}, this.value)">
              </td>
              <td>${formatRupiah(item.subtotal)}</td>
              <td><button onclick="hapusItem(${index})" class="btn btn-sm btn-danger">Hapus</button></td>
            </tr>
          `;
                });

                // Hitung diskon, pajak, dan total akhir
                let diskon = subtotalKeseluruhan >= 100000 ? subtotalKeseluruhan * 0.05 : 0;
                let pajak = subtotalKeseluruhan * 0.12;
                let selectedPelanggan = document.getElementById("pilihPelanggan").selectedOptions[0];
                let tipe = selectedPelanggan.value ? selectedPelanggan.getAttribute("data-tipe") : null;
                let poinDidapat = (tipe && (tipe === "Perak" || tipe === "Emas")) ? subtotalKeseluruhan * 0.02 : 0;
                let totalAkhir = subtotalKeseluruhan - diskon + pajak - poinDigunakan;
                if (totalAkhir < 0) totalAkhir = 0;

                // Tampilkan nilai yang telah diformat
                document.getElementById("subtotalKeseluruhan").value = formatRupiah(subtotalKeseluruhan);
                document.getElementById("diskon").value = formatRupiah(diskon);
                document.getElementById("pajak").value = formatRupiah(pajak);
                document.getElementById("totalAkhir").value = formatRupiah(totalAkhir);
                document.getElementById("poinDidapat").value = poinDidapat.toFixed(2);

                // Simpan nilai numerik ke hidden field
                document.getElementById("subtotalKeseluruhanVal").value = subtotalKeseluruhan;
                document.getElementById("diskonVal").value = diskon;
                document.getElementById("pajakVal").value = pajak;
                document.getElementById("totalAkhirVal").value = totalAkhir;
            }

            // Hitung kembalian berdasarkan nilai numerik totalAkhir (dari hidden field)
            function hitungKembalian() {
                let totalAkhir = parseFloat(document.getElementById("totalAkhirVal").value) || 0;
                let pembayaran = parseFloat(document.getElementById("pembayaran").value) || 0;

                let kembalianInput = document.getElementById("kembalian");
                let simpanBtn = document.getElementById("simpanTransaksiBtn");

                if (pembayaran < totalAkhir) {
                    kembalianInput.value = "Uang pembayaran kurang";
                    kembalianInput.style.color = "red";
                    simpanBtn.disabled = true;
                    simpanBtn.classList.remove("btn-primary");
                    simpanBtn.classList.add("btn-secondary");
                } else {
                    let kembalian = pembayaran - totalAkhir;
                    kembalianInput.value = formatRupiah(kembalian);
                    kembalianInput.style.color = "black";
                    simpanBtn.disabled = false;
                    simpanBtn.classList.remove("btn-secondary");
                    simpanBtn.classList.add("btn-primary");
                }
            }

            // Simpan transaksi melalui AJAX dengan konfirmasi SweetAlert
            function simpanTransaksi() {
  // Validasi: cek apakah pelanggan sudah dipilih
  let pelangganId = document.getElementById("pilihPelanggan").value;
  if (!pelangganId) {
    Swal.fire({
      icon: 'warning',
      title: 'Pelanggan Belum Dipilih',
      text: 'Silakan pilih pelanggan terlebih dahulu sebelum menyimpan transaksi'
    });
    return;
  }

  Swal.fire({
    title: 'Konfirmasi Transaksi',
    text: 'Apakah Anda yakin untuk menyimpan transaksi ini?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Ya, Simpan',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      let dataTransaksi = {
        pelanggan_id: pelangganId,
        items: transaksi,
        poin_digunakan: parseFloat(document.getElementById("poinDigunakan").value) || 0,
        pembayaran: parseFloat(document.getElementById("pembayaran").value) || 0,
        subtotal: parseFloat(document.getElementById("subtotalKeseluruhanVal").value),
        diskon: parseFloat(document.getElementById("diskonVal").value),
        pajak: parseFloat(document.getElementById("pajakVal").value),
        total: parseFloat(document.getElementById("totalAkhirVal").value)
      };

      let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch("{{ route('kasir.store') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify(dataTransaksi)
        })
        .then(response => {
          if (!response.ok) {
            return response.text().then(text => {
              throw new Error(text);
            });
          }
          return response.json();
        })
        .then(result => {
          Swal.fire({
            title: 'Berhasil!',
            text: 'Transaksi berhasil disimpan.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
          });
          // Redirect ke invoice setelah 2 detik
          setTimeout(() => {
            window.location.href = result.redirect_url;
          }, 2000);
        })
        .catch(error => {
          console.error("Error:", error);
          Swal.fire('Error', 'Terjadi kesalahan saat menyimpan transaksi', 'error');
        });
    }
  });
}


            // Ubah jumlah produk dan validasi stok
            function ubahJumlah(index, jumlah) {
                jumlah = parseInt(jumlah);

                let optionEl = document.querySelector(`#pilihProduk option[value="${transaksi[index].produk_id}"]`);
                if (optionEl) {
                    let stok = parseInt(optionEl.getAttribute("data-stok"));
                    if (jumlah > stok) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Stok Tidak Mencukupi',
                            text: `Stok yang tersedia untuk ${transaksi[index].nama} hanya ${stok} unit.`,
                        });
                        jumlah = stok;
                    }
                }

                transaksi[index].jumlah = jumlah;
                transaksi[index].subtotal = transaksi[index].harga * jumlah;
                renderTable();
            }

            // Hapus item dari transaksi
            function hapusItem(index) {
                transaksi.splice(index, 1);
                renderTable();
            }

            // Fungsi untuk memformat angka ke format rupiah
            function formatRupiah(angka) {
                return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0
                }).format(angka);
            }

            // Event listener untuk perubahan pelanggan dan produk
            document.getElementById("pilihPelanggan").addEventListener("change", updatePelanggan);
            document.getElementById("pilihProduk").addEventListener("change", tambahProduk);
        </script>
    </div>
</body>

</html>

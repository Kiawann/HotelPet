<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rincian Reservasi Hotel</title>
    <style>
        .container {
            max-width: 90%;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            padding: 20px;
        }

        .card-header {
            background-color: #2980b9;
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .card-body {
            padding: 20px;
        }

        h4 {
            font-size: 1.25em;
            color: #2980b9;
            margin-bottom: 20px;
        }

        .ruangan-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .ruangan-item h5 {
            margin: 0 0 10px;
            font-size: 1.1em;
            color: #2980b9;
        }

        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
            padding: 8px 16px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
            padding: 8px 16px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .total-price {
            font-size: 1.1em;
            font-weight: bold;
            color: #e74c3c;
            margin-top: 20px;
            text-align: right;
        }

        .section-title {
            font-size: 1.2em;
            color: #2980b9;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .button-group {
            text-align: center;
            margin-top: 30px;
        }

        /* Responsive styles */
        @media (max-width: 600px) {
            h4 {
                font-size: 1.1em;
            }

            .btn-primary,
            .btn-danger {
                padding: 6px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    @include('layouts.navbar')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Rincian Reservasi Hotel</h3>
            </div>
            <div class="card-body">

                <!-- Informasi Reservasi -->
                <div class="mb-4">
                    <h4 class="section-title">Informasi Reservasi</h4>
                    <table>
                        <tr>
                            <th>Nama Pemilik</th>
                            <td>{{ $reservasiHotel->dataPemilik->nama ?? 'Tidak ada data pemilik' }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td>{{ $reservasiHotel->dataPemilik->nomor_telp ?? 'Tidak ada telepon' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Rincian Ruangan dan Hewan -->
                <div>
                    <h4 class="section-title">Rincian Ruangan dan Hewan</h4>
                    @php
                        $totalHarga = 0;
                    @endphp
                    @foreach ($reservasiHotel->rincianReservasiHotel->groupBy('room_id') as $roomId => $rincianGroup)
                        @php
                            $room = $rincianGroup->first()->room;
                            $subtotal = $rincianGroup->sum('SubTotal');
                            $totalHarga += $subtotal;
                        @endphp
                        <div class="ruangan-item">
                            <h5>{{ $room->nama_ruangan ?? 'Room tidak tersedia' }}</h5>
                            <p><strong>Harga per Malam:</strong> Rp {{ number_format($room->category_hotel->harga, 0, ',', '.') }}</p>
                            <p><strong>Nama Hewan:</strong>
                                @foreach ($rincianGroup as $rincian)
                                    {{ $rincian->dataHewan->nama_hewan ?? 'Tidak ada hewan' }}<br>
                                @endforeach
                            </p>
                            <p><strong>Jenis Kelamin:</strong>
                                @foreach ($rincianGroup as $rincian)
                                    {{ $rincian->dataHewan->jenis_kelamin ?? 'Tidak diketahui' }}<br>
                                @endforeach
                            </p>
                            <p><strong>Ras:</strong>
                                @foreach ($rincianGroup as $rincian)
                                    {{ $rincian->dataHewan->ras_hewan ?? 'Tidak diketahui' }}<br>
                                @endforeach
                            </p>
                            <p><strong>Tanggal Check In:</strong>
                                @foreach ($rincianGroup as $rincian)
                                    {{ \Carbon\Carbon::parse($rincian->tanggal_checkin)->format('d/m/Y') }}<br>
                                @endforeach
                            </p>
                            <p><strong>Tanggal Check Out:</strong>
                                @foreach ($rincianGroup as $rincian)
                                    {{ \Carbon\Carbon::parse($rincian->tanggal_checkout)->format('d/m/Y') }}<br>
                                @endforeach
                            </p>
                            <p><strong>Subtotal:</strong> Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                    <h5 class="total-price">Total: Rp {{ number_format($totalHarga, 0, ',', '.') }}</h5>
                </div>

                <div class="button-group" style="display: flex; flex-direction: column; align-items: center; margin-top: 30px;">
                    <div style="display: flex; justify-content: center; width: 100%; flex-wrap: wrap; gap: 10px;">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary d-flex align-items-center justify-content-center"
                            style="min-width: 150px; height: 40px; padding: 0 15px;">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                
                        @if ($reservasiHotel->transaksi && $reservasiHotel->transaksi->id)
                            <button type="button" class="btn btn-info d-flex align-items-center justify-content-center"
                                data-bs-toggle="modal" data-bs-target="#transaksiModal"
                                style="min-width: 150px; height: 40px; padding: 0 15px;">
                                <i class="fas fa-info-circle me-2"></i> Detail Pembayaran
                            </button>
                        @else
                            @if (
                                $reservasiHotel->status !== 'cancel' &&
                                $reservasiHotel->status !== 'di bayar' &&
                                $reservasiHotel->status !== 'check in' &&
                                $reservasiHotel->status !== 'check out' &&
                                $reservasiHotel->status !== 'done'
                            )
                                <a href="{{ route('transaksi.create', ['reservasi_hotel_id' => $reservasiHotel->id]) }}"
                                    class="btn btn-primary d-flex align-items-center justify-content-center"
                                    style="min-width: 150px; height: 40px; padding: 0 15px;">
                                    Bayar
                                </a>
                            @endif
                        @endif
                
                        @if (
                            $reservasiHotel->status !== 'cancel' &&
                            $reservasiHotel->status !== 'di bayar' &&
                            $reservasiHotel->status !== 'check in' &&
                            $reservasiHotel->status !== 'check out' &&
                            $reservasiHotel->status !== 'done'
                        )
                            <a href="{{ route('reservasi.cancel', $reservasiHotel->id) }}"
                                class="btn btn-danger d-flex align-items-center justify-content-center"
                                style="min-width: 150px; height: 40px; padding: 0 15px;">
                                Batal Reservasi
                            </a>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
    </div>

 <!-- Modal Detail Transaksi -->
<div class="modal fade" id="transaksiModal" tabindex="-1" aria-labelledby="transaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen-sm-down"> <!-- Fullscreen di layar kecil -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transaksiModalLabel">Detail Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($reservasiHotel->transaksi->id)
                    <div class="card shadow-sm p-3">
                        <div class="mb-2">
                            <strong>ID Transaksi:</strong>
                            <p>{{ $reservasiHotel->transaksi->id }}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Tanggal Pembayaran:</strong>
                            <p>{{ \Carbon\Carbon::parse($reservasiHotel->transaksi->tanggal_pembayaran)->format('d/m/Y') }}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Subtotal:</strong>
                            <p>Rp {{ number_format($reservasiHotel->transaksi->Subtotal, 0, ',', '.') }}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Status Pembayaran:</strong>
                            <p>{{ $reservasiHotel->transaksi->status_pembayaran }}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Foto Transfer:</strong>
                            <div>
                                @if ($reservasiHotel->transaksi->Foto_Transfer)
                                    <img src="{{ asset('storage/' . $reservasiHotel->transaksi->Foto_Transfer) }}" 
                                         alt="Foto Transfer" 
                                         class="img-fluid rounded shadow"
                                         style="max-width: 100px; cursor: pointer;"
                                         onclick="showFullImage('{{ asset('storage/' . $reservasiHotel->transaksi->Foto_Transfer) }}')">
                                @else
                                    <p class="text-muted">Tidak ada foto transfer</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-center text-muted">Tidak ada transaksi untuk reservasi ini.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


    <!-- Modal untuk menampilkan gambar full -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body text-center p-0">
                    <img id="fullImage" src="" alt="Full Image" style="max-width: 100%; height: auto;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        function showFullImage(imageSrc) {
            document.getElementById('fullImage').src = imageSrc;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
    </script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function(e) {
            e.preventDefault();
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';

            const data = {
                amount: {{ $totalHarga }},
                first_name: '{{ $reservasiHotel->dataPemilik->nama ?? '' }}',
                last_name: '',
                email: '{{ $reservasiHotel->dataPemilik->user->email ?? '' }}',
                phone: '{{ $reservasiHotel->dataPemilik->nomor_telp ?? '' }}',
                reservasi_id: {{ $reservasiHotel->id }}
            };

            fetch("{{ route('payment.pay') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    const payButton = document.getElementById('pay-button');
                    payButton.disabled = false;
                    payButton.innerHTML = 'Bayar Sekarang';

                    snap.pay(data.token, {
                        onSuccess: function(result) {
                            fetch(`/payment/update-status/${data.reservasi_id}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }).then(() => {
                                window.location.href = data.redirect_url;
                            });
                        },
                        onPending: function(result) {
                            alert('Transaksi pending, silahkan selesaikan pembayaran');
                            window.location.href = data.redirect_url;
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal, silakan coba lagi');
                            payButton.disabled = false;
                            payButton.innerHTML = 'Bayar Sekarang';
                        },
                        onClose: function() {
                            payButton.disabled = false;
                            payButton.innerHTML = 'Bayar Sekarang';
                        }
                    });
                })
                .catch(error => {
                    console.error('Payment error:', error);
                    alert('Terjadi kesalahan saat memproses pembayaran');

                    const payButton = document.getElementById('pay-button');
                    payButton.disabled = false;
                    payButton.innerHTML = 'Bayar Sekarang';
                });
        });
    </script>
</body>

</html>
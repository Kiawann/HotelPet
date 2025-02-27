<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Hotel - Form Pemesanan</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
</head>

<style>
    body {
        background-color: #f9f9f9;
    }

    .form-container {
        background: #f7f9fc;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .custom-input,
    .custom-select {
        border-radius: 25px;
        border: 1px solid #ddd;
        padding: 12px 18px;
        transition: border-color 0.3s ease;
    }

    .custom-input:focus,
    .custom-select:focus {
        border-color: #4CAF50;
    }

    .custom-button {
        background: linear-gradient(to right, #4CAF50, #45a049);
        font-weight: bold;
        color: #fff;
    }

    .custom-button:hover {
        background: linear-gradient(to right, #45a049, #4CAF50);
    }

    .btn-danger {
        border-radius: 50px;
    }

    .alert-list {
        list-style-type: none;
        padding-left: 0;
        margin: 0;
    }

    .alert-list li {
        margin-bottom: 5px;
    }

    .alert-list li:last-child {
        margin-bottom: 0;
    }
</style>

<body>
    @include('layouts.navbar')

    <div class="container mt-5 pt-5">
        <h1 class="mb-4 text-center text-primary">Form Pemesanan Hotel</h1>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="alert-list">
                @php
                    $bookedPets = [];
                    $roomErrors = [];
                    $otherErrors = [];
    
                    foreach ($errors->all() as $error) {
                        if (strpos($error, 'sudah dipesan untuk tanggal') !== false) {
                            $bookedPets[] = $error;
                        } elseif (strpos($error, 'Kamar') !== false && strpos($error, 'sudah dipesan dari') !== false) {
                            $roomErrors[] = $error;
                        } else {
                            $otherErrors[] = $error;
                        }
                    }
                @endphp
    
                @if (!empty($bookedPets))
                    @foreach ($bookedPets as $petError)
                        <li>{{ $petError }}</li>
                    @endforeach
                @endif
    
                @if (!empty($roomErrors))
                    @foreach ($roomErrors as $roomError)
                        <li>{{ $roomError }}</li>
                    @endforeach
                @endif
    
                @foreach ($otherErrors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class="form-container bg-gradient p-5 rounded-4 shadow-lg">
            <form action="{{ route('booking.store') }}" method="POST">
                @csrf

                <!-- Data Pemilik -->
                <div class="mb-4">
                    <label for="data_pemilik_id" class="form-label">
                        <i class="fas fa-user me-2"></i> Pemilik:
                    </label>
                    @if ($dataPemilik)
                        <input type="text" class="form-control" value="{{ $dataPemilik->nama }}" readonly>
                        <input type="hidden" name="data_pemilik_id" value="{{ $dataPemilik->id }}">
                    @else
                        <p>No pemilik found</p>
                    @endif
                </div>
                {{-- 
                <!-- Tanggal Check-in -->
                <div class="mb-4">
                    <label for="tanggal_checkin" class="form-label">
                        <i class="fas fa-calendar-alt me-2"></i> Tanggal Check-in:
                    </label>
                    <input type="date" name="tanggal_checkin" class="form-control custom-input">
                    @error('tanggal_checkin')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Check-out -->
                <div class="mb-4">
                    <label for="tanggal_checkout" class="form-label">
                        <i class="fas fa-calendar-check me-2"></i> Tanggal Check-out:
                    </label>
                    <input type="date" name="tanggal_checkout" class="form-control custom-input">
                    @error('tanggal_checkout')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Booking -->
                <div id="bookingForms">
                    <div class="booking-form mb-4">
                        <div class="row">
                            <!-- Pilihan Hewan -->
                            <div class="col-6">
                                <label for="data_hewan_id" class="form-label">
                                    <i class="fas fa-paw me-2"></i> Hewan:
                                </label>
                                <select name="data_hewan_id[]" class="form-select custom-select">
                                    <option value="">Pilih Hewan</option>
                                    @foreach ($dataHewan as $hewan)
                                        <option value="{{ $hewan->id }}">{{ $hewan->nama_hewan }}</option>
                                    @endforeach
                                </select>
                                @error('data_hewan_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pilihan Room -->
                            <div class="col-6">
                                <label for="room_id" class="form-label">
                                    <i class="fas fa-door-open me-2"></i> Room:
                                </label>
                                <select name="room_id[]" class="form-select custom-select">
                                    <option value="">Pilih Room</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}">
                                            {{ $room->nama_ruangan }} - {{ $room->category_hotel->nama_kategori }} -
                                            Rp{{ number_format($room->category_hotel->harga, 0, ',', '.') }} / malam
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- Tanggal Check-in -->
                <div class="mb-4">
                    <label for="tanggal_checkin" class="form-label">
                        <i class="fas fa-calendar-alt me-2"></i> Tanggal Check-in:
                    </label>
                    <input type="date" name="tanggal_checkin" class="form-control custom-input"
                        value="{{ old('tanggal_checkin') }}">
                    @error('tanggal_checkin')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Check-out -->
                <div class="mb-4">
                    <label for="tanggal_checkout" class="form-label">
                        <i class="fas fa-calendar-check me-2"></i> Tanggal Check-out:
                    </label>
                    <input type="date" name="tanggal_checkout" class="form-control custom-input"
                        value="{{ old('tanggal_checkout') }}">
                    @error('tanggal_checkout')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Booking -->
                <div id="bookingForms">
                    @foreach (old('data_hewan_id', ['']) as $index => $oldHewan)
                        <div class="booking-form mb-4">
                            <div class="row">
                                <!-- Pilihan Hewan -->
                                <div class="col-6">
                                    <label for="data_hewan_id" class="form-label">
                                        <i class="fas fa-paw me-2"></i> Hewan:
                                    </label>
                                    <select name="data_hewan_id[]" class="form-select custom-select">
                                        <option value="">Pilih Hewan</option>
                                        @foreach ($dataHewan as $hewan)
                                            <option value="{{ $hewan->id }}"
                                                {{ old('data_hewan_id.' . $index) == $hewan->id ? 'selected' : '' }}>
                                                {{ $hewan->nama_hewan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('data_hewan_id.' . $index)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Pilihan Room -->
                                <div class="col-6">
                                    <label for="room_id" class="form-label">
                                        <i class="fas fa-door-open me-2"></i> Room:
                                    </label>
                                    <select name="room_id[]" class="form-select custom-select">
                                        <option value="">Pilih Room</option>
                                        @foreach ($rooms as $room)
                                            <option value="{{ $room->id }}"
                                                {{ old('room_id.' . $index) == $room->id ? 'selected' : '' }}>
                                                {{ $room->nama_ruangan }} -
                                                {{ $room->category_hotel->nama_kategori }}
                                                -
                                                Rp{{ number_format($room->category_hotel->harga, 0, ',', '.') }}
                                                /
                                                malam
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_id.' . $index)
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Tombol Tambah Form -->
                <div class="mb-4 d-flex justify-content-center">
                    <button type="button" id="addBookingForm" class="btn btn-secondary px-4 py-2 rounded-pill">
                        <i class="fas fa-plus me-2"></i> Tambah Form Pemesanan
                    </button>
                </div>

                <!-- Tombol Submit dan Kembali -->
                <div class="d-flex justify-content-center gap-3">
                    <button type="submit" class="btn btn-success px-4 py-2 rounded-pill custom-button">
                        <i class="fas fa-check me-2"></i> Pesan
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary px-4 py-2 rounded-pill">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingFormsContainer = document.getElementById('bookingForms');
            const addBookingFormButton = document.getElementById('addBookingForm');

            let selectedAnimals = [];
            let selectedRooms = [];

            // Function to update animal select options
            const updateAnimalOptions = () => {
                const allAnimalSelects = bookingFormsContainer.querySelectorAll(
                    'select[name="data_hewan_id[]"]');
                allAnimalSelects.forEach(select => {
                    const currentSelected = select.value;
                    Array.from(select.options).forEach(option => {
                        if (option.value && selectedAnimals.includes(option.value) && option
                            .value !== currentSelected) {
                            option.style.display = 'none';
                        } else {
                            option.style.display = 'block';
                        }
                    });
                });
            };

            // Function to update room select options
            const updateRoomOptions = () => {
                const allRoomSelects = bookingFormsContainer.querySelectorAll('select[name="room_id[]"]');
                allRoomSelects.forEach(select => {
                    const currentSelected = select.value;
                    Array.from(select.options).forEach(option => {
                        if (option.value && selectedRooms.includes(option.value) && option
                            .value !== currentSelected) {
                            option.style.display = 'none';
                        } else {
                            option.style.display = 'block';
                        }
                    });
                });
            };

            // Function to update the selected animals and rooms lists
            const updateSelectedLists = () => {
                selectedAnimals = Array.from(bookingFormsContainer.querySelectorAll(
                        'select[name="data_hewan_id[]"]'))
                    .map(select => select.value)
                    .filter(value => value);

                selectedRooms = Array.from(bookingFormsContainer.querySelectorAll('select[name="room_id[]"]'))
                    .map(select => select.value)
                    .filter(value => value);
            };

            // Function to update remove buttons on all forms
            const updateAllRemoveButtons = () => {
                const forms = bookingFormsContainer.querySelectorAll('.booking-form');
                forms.forEach(form => {
                    const existingButton = form.querySelector('.removeBookingForm');
                    if (existingButton) {
                        existingButton.closest('.text-center').remove();
                    }

                    if (forms.length > 1) {
                        const removeButton = document.createElement('div');
                        removeButton.classList.add('text-center', 'mt-2');
                        removeButton.innerHTML = `
                    <button type="button" class="btn btn-danger btn-sm removeBookingForm d-flex align-items-center justify-content-center">
                        <i class="fas fa-trash me-2"></i> Batal
                    </button>
                `;
                        form.appendChild(removeButton);
                    }
                });
            };

            // Function to create a new booking form
            const createBookingForm = () => {
                const newForm = document.createElement('div');
                newForm.classList.add('booking-form', 'mb-4');
                newForm.innerHTML = `
            <div class="row">
                <div class="col-6">
                    <label for="data_hewan_id" class="form-label d-flex align-items-center">
                        <i class="fas fa-paw me-2"></i> Hewan:
                    </label>
                    <select name="data_hewan_id[]" class="form-select custom-select">
                        <option value="">Pilih Hewan</option>
                        @foreach ($dataHewan as $hewan)
                            <option value="{{ $hewan->id }}">{{ $hewan->nama_hewan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label for="room_id" class="form-label d-flex align-items-center">
                        <i class="fas fa-door-open me-2"></i> Room:
                    </label>
                    <select name="room_id[]" class="form-select custom-select">
                        <option value="">Pilih Room</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">
                                {{ $room->nama_ruangan }} - {{ $room->category_hotel->nama_kategori }} - Rp{{ number_format($room->category_hotel->harga, 0, ',', '.') }} / malam
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        `;
                return newForm;
            };

            // Event listener for select changes
            bookingFormsContainer.addEventListener('change', function(e) {
                if (e.target.name === 'data_hewan_id[]' || e.target.name === 'room_id[]') {
                    updateSelectedLists();
                    updateAnimalOptions();
                    updateRoomOptions();
                }
            });

            // Event listener for adding new booking form
            addBookingFormButton.addEventListener('click', function() {
                const newForm = createBookingForm();
                bookingFormsContainer.appendChild(newForm);
                updateSelectedLists();
                updateAnimalOptions();
                updateRoomOptions();
                updateAllRemoveButtons();
            });

            // Event listener for removing booking form
            bookingFormsContainer.addEventListener('click', function(e) {
                const removeButton = e.target.closest('.removeBookingForm');
                if (removeButton) {
                    const form = removeButton.closest('.booking-form');
                    const animalSelect = form.querySelector('select[name="data_hewan_id[]"]');
                    const roomSelect = form.querySelector('select[name="room_id[]"]');

                    // Clear selections before removing
                    if (animalSelect) animalSelect.value = '';
                    if (roomSelect) roomSelect.value = '';

                    // Update selected lists
                    updateSelectedLists();

                    // Remove the form
                    form.remove();

                    // Update everything
                    updateAnimalOptions();
                    updateRoomOptions();
                    updateAllRemoveButtons();
                }
            });

            // Initialize the form
            updateSelectedLists();
            updateAnimalOptions();
            updateRoomOptions();
            updateAllRemoveButtons();
        });

        // Tambahkan fungsi untuk menandai input tanggal dengan error
function markDateInputError(errorDates) {
    const checkinInput = document.querySelector('input[name="tanggal_checkin"]');
    const checkoutInput = document.querySelector('input[name="tanggal_checkout"]');
    
    if (errorDates) {
        // Tambahkan styling error
        checkinInput.style.border = '2px solid red';
        checkoutInput.style.border = '2px solid red';
        
        // Tambahkan fokus ke input tanggal check-in
        checkinInput.focus();
        
        // Opsional: Tambahkan tooltip atau pesan error
        checkinInput.setAttribute('title', 'Terdapat konflik reservasi pada tanggal ini');
        checkoutInput.setAttribute('title', 'Terdapat konflik reservasi pada tanggal ini');
    }
}

// Panggil fungsi saat halaman dimuat jika ada error
document.addEventListener('DOMContentLoaded', function() {
    const errorContainer = document.querySelector('.alert-danger');
    
    if (errorContainer) {
        const roomErrorMessages = errorContainer.querySelectorAll('li');
        
        roomErrorMessages.forEach(message => {
            if (message.textContent.includes('sudah dipesan dari')) {
                markDateInputError(true);
            }
        });
    }
});

function markDateInputError(errorDates) {
    const checkinInput = document.querySelector('input[name="tanggal_checkin"]');
    const checkoutInput = document.querySelector('input[name="tanggal_checkout"]');
    
    if (errorDates) {
        checkinInput.style.border = '2px solid red';
        checkoutInput.style.border = '2px solid red';
        
        checkinInput.focus();
        
        checkinInput.setAttribute('title', 'Terdapat konflik reservasi pada tanggal ini');
        checkoutInput.setAttribute('title', 'Terdapat konflik reservasi pada tanggal ini');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const errorContainer = document.querySelector('.alert-danger');
    
    if (errorContainer) {
        const errorMessages = errorContainer.querySelectorAll('li');
        
        errorMessages.forEach(message => {
            if (message.textContent.includes('sudah reservasi dari')) {
                markDateInputError(true);
            }
        });
    }
});
    </script>

</body>

</html>

@extends('layouts.app')

@section('title', 'Edit Profil Siswa - ' . config('app.name'))

@section('styles')
    <style>
        .bg-mesh {
            background-color: #f0fdf4;
            background-image: 
                radial-gradient(at 20% 20%, rgba(52, 211, 153, 0.15) 0px, transparent 50%),
                radial-gradient(at 80% 0%, rgba(16, 185, 129, 0.1) 0px, transparent 50%),
                radial-gradient(at 0% 80%, rgba(167, 243, 208, 0.15) 0px, transparent 50%),
                radial-gradient(at 80% 80%, rgba(52, 211, 153, 0.1) 0px, transparent 50%);
        }
        .card-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.05), 0 4px 20px -5px rgba(0, 0, 0, 0.03);
        }
        .input-modern {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1.5px solid #e5e7eb;
        }
        .input-modern:hover:not(:disabled):not([readonly]) { border-color: #d1d5db; }
        .input-modern:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        .input-modern:disabled, .input-modern[readonly] {
            background-color: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }
        .input-modern.border-red-400 { border-color: #f87171; }
        .input-modern.border-red-400:focus { box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.1); border-color: #f87171; }
        .input-icon { transition: color 0.3s ease; }
        .input-group:focus-within .input-icon { color: #059669; }
        
        .upload-area { border: 2px dashed #e5e7eb; transition: all 0.3s ease; }
        .upload-area:hover { border-color: #10b981; background-color: #f0fdf4; }
        
        .btn-cancel { transition: all 0.3s ease; }
        .btn-cancel:hover { background-color: #e5e7eb; }
        .btn-save {
            background: linear-gradient(135deg, #059669, #047857);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-save:hover:not(:disabled) {
            background: linear-gradient(135deg, #047857, #065f46);
            box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.4);
        }
        .btn-save:disabled { opacity: 0.7; cursor: not-allowed; }
    </style>
@stop

@section('content')
    <div class="min-h-screen bg-mesh flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-3xl">
            <div class="card-glass rounded-2xl overflow-hidden animate-fade-in">
                
                {{-- Header --}}
                <div class="bg-gradient-to-br from-emerald-600 to-green-700 text-white p-10 text-center relative">
                    <div class="absolute inset-0 bg-black/5"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20">
                            <i class="fas fa-user-pen text-2xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight mb-1">Edit Profil Siswa</h1>
                        <p class="text-green-100 text-sm">Perbarui informasi akun Anda di bawah ini.</p>
                    </div>
                </div>

                {{-- Form Area --}}
                <div class="p-8">
                    <!-- Container untuk pesan Sukses/Error dari AJAX -->
                    <div id="alertContainer"></div>

                    <form id="editProfileForm" action="{{ route('student.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        {{-- CATATAN: Jika route kamu memakai PUT/PATCH, biarkan @method('PUT') di bawah ini. 
                                     Jika route kamu memakai POST, hapus baris @method di bawah ini. --}}

                        {{-- === Informasi Pribadi === --}}
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs"><i class="fas fa-user"></i></div>
                                Informasi Pribadi
                            </h3>
                            <div class="grid md:grid-cols-2 gap-5">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                                    <div class="relative input-group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-user input-icon text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="text" name="name" id="name" value="{{ old('name', $student->name) }}" required
                                            class="input-modern w-full pl-11 pr-4 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                            placeholder="Masukkan nama lengkap">
                                    </div>
                                </div>

                                {{-- NIS (DIBUAT READONLY) --}}
                                <div>
                                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-1.5">NIS <span class="text-gray-400 font-normal">(Tidak dapat diubah)</span></label>
                                    <div class="relative input-group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-id-card input-icon text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="text" name="nis" id="nis" value="{{ $student->nis }}" readonly
                                            class="input-modern w-full pl-11 pr-4 py-3 rounded-xl outline-none"
                                            placeholder="Contoh: 20250001">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- === Sekolah & Kontak === --}}
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs"><i class="fas fa-school"></i></div>
                                Sekolah & Kontak
                            </h3>
                            <div class="grid md:grid-cols-2 gap-5">
                                
                                {{-- KELAS (DIBUAT READONLY) --}}
                                <div>
                                    <label for="class" class="block text-sm font-medium text-gray-700 mb-1.5">Kelas <span class="text-gray-400 font-normal">(Tidak dapat diubah)</span></label>
                                    <div class="relative input-group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-graduation-cap input-icon text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="text" name="class" id="class" value="{{ $student->class }}" readonly 
                                            class="input-modern w-full pl-11 pr-4 py-3 rounded-xl outline-none"
                                            placeholder="Contoh: XII Akuntansi">
                                    </div>
                                </div>

                                <div>
                                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon / WA</label>
                                    <div class="relative input-group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-phone input-icon text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="tel" name="contact" id="contact" value="{{ old('contact', $student->contact) }}"
                                            class="input-modern w-full pl-11 pr-4 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                            placeholder="081234567890">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- === Informasi Akun === --}}
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs"><i class="fas fa-shield-halved"></i></div>
                                Informasi Akun
                            </h3>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                <div class="relative input-group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-at input-icon text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email', $student->email) }}" required
                                        class="input-modern w-full pl-11 pr-4 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                        placeholder="nama@email.com">
                                </div>
                            </div>
                        </div>

                        {{-- === Upload Avatar === --}}
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs"><i class="fas fa-camera"></i></div>
                                Foto Profil
                            </h3>
                            <div class="flex flex-col sm:flex-row gap-5 items-start">
                                <div class="flex-shrink-0">
                                    <div id="avatarPreviewContainer" data-old-avatar="{{ $student->avatar ? asset('storage/' . $student->avatar) : 'null' }}" class="w-28 h-28 rounded-xl bg-gray-100 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden relative">
                                        @if ($student->avatar)
                                            <img src="{{ asset('storage/' . $student->avatar) }}" alt="Avatar" class="w-full h-full object-cover" id="currentAvatar">
                                        @else
                                            <div class="text-center text-gray-400">
                                                <i class="fas fa-user text-2xl mb-1"></i>
                                                <p class="text-[10px]">Belum ada foto</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 w-full">
                                    <div class="upload-area rounded-xl p-4 text-center cursor-pointer relative">
                                        <input type="file" name="avatar" id="avatar" accept="image/png,image/jpeg,image/gif" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="pointer-events-none">
                                            <i class="fas fa-cloud-arrow-up text-gray-400 text-xl mb-2"></i>
                                            <p class="text-sm text-gray-600 font-medium">Klik untuk pilih foto</p>
                                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, atau GIF (Maks. 2MB)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- === Tombol Aksi === --}}
                        <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-gray-100 mt-2">
                            <a href="{{ route('student.dashboard') }}" wire:navigate.prefetch="false"
                                class="btn-cancel w-full sm:w-1/2 bg-gray-100 text-gray-600 py-3.5 rounded-xl font-semibold flex items-center justify-center gap-2 text-sm border border-gray-200">
                                <i class="fas fa-arrow-left text-xs"></i>
                                Batal
                            </a>
                            <button type="submit" id="submitButton"
                                class="btn-save w-full sm:w-1/2 text-white py-3.5 rounded-xl font-semibold flex items-center justify-center gap-2 text-sm">
                                <i class="fas fa-floppy-disk text-xs" id="btnIcon"></i>
                                <span id="btnText">Simpan Perubahan</span>
                                <div id="buttonLoader" class="hidden">
                                    <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
    function initEditProfileScripts() {
        const avatarInput = document.getElementById('avatar');
        const previewContainer = document.getElementById('avatarPreviewContainer');
        const form = document.getElementById('editProfileForm');
        const submitBtn = document.getElementById('submitButton');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');
        const btnLoader = document.getElementById('buttonLoader');
        const alertContainer = document.getElementById('alertContainer');

        if (!form) return; // Berhenti jika bukan di halaman edit profil

        // --- Preview Avatar Logic ---
        if (avatarInput) {
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran foto maksimal adalah 2MB.');
                        this.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                            <button type="button" id="removeAvatar" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs shadow-md hover:bg-red-600 transition-colors z-20">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        document.getElementById('removeAvatar').addEventListener('click', function() {
                            resetPreview();
                        });
                    }
                    reader.readAsDataURL(file);
                } else {
                    resetPreview();
                }
            });
        }

        // Fungsi reset preview membutuhkan URL avatar lama. 
        // Kita ambil dari atribut data yang akan kita taruh di HTML
        function resetPreview() {
            if (avatarInput) avatarInput.value = '';
            const oldAvatarUrl = previewContainer.getAttribute('data-old-avatar');
            
            if (oldAvatarUrl && oldAvatarUrl !== 'null') {
                previewContainer.innerHTML = `<img src="${oldAvatarUrl}" alt="Avatar" class="w-full h-full object-cover">`;
            } else {
                previewContainer.innerHTML = `
                    <div class="text-center text-gray-400">
                        <i class="fas fa-user text-2xl mb-1"></i>
                        <p class="text-[10px]">Belum ada foto</p>
                    </div>`;
            }
        }

        // --- Form Submit AJAX ---
        form.addEventListener('submit', function(e) {
            e.preventDefault(); 
            alertContainer.innerHTML = '';

            submitBtn.disabled = true;
            btnIcon.classList.add('hidden');
            btnLoader.classList.remove('hidden');
            btnText.textContent = 'Menyimpan...';

            const formData = new FormData(form);
            
            fetch(form.action, { 
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                return response.text().then(text => {
                    try {
                        let data = JSON.parse(text);
                        if (response.ok) return data; 
                        else throw data;   
                    } catch (e) {
                        throw { message: text }; 
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    alertContainer.innerHTML = `
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-4 rounded-xl flex items-start mb-6">
                            <i class="fas fa-check-circle mr-3 mt-0.5 text-emerald-500"></i>
                            <span>${data.success}</span>
                        </div>`;
                    
                    if(data.avatar_url) {
                        previewContainer.setAttribute('data-old-avatar', data.avatar_url); // Update URL lama
                        previewContainer.innerHTML = `<img src="${data.avatar_url}" alt="Avatar" class="w-full h-full object-cover">`;
                        if (avatarInput) avatarInput.value = '';
                    }
                }
            })
            .catch(error => {
                if (error.errors) {
                    let errorMessages = Object.values(error.errors).map(err => `<li>${err[0]}</li>`).join('');
                    alertContainer.innerHTML = `
                        <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-xl flex items-start mb-6">
                            <i class="fas fa-exclamation-triangle mr-3 mt-0.5 text-red-500"></i>
                            <div>
                                <p class="font-medium mb-1">Gagal menyimpan:</p>
                                <ul class="list-disc list-inside space-y-0.5 text-red-500">${errorMessages}</ul>
                            </div>
                        </div>`;
                } else {
                    alert('Terjadi kesalahan. Buka Tab Console (F12) untuk lihat detailnya.');
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                btnIcon.classList.remove('hidden');
                btnLoader.classList.add('hidden');
                btnText.textContent = 'Simpan Perubahan';
            });
        });
    }

    // Jalankan saat pertama kali load
    document.addEventListener('DOMContentLoaded', initEditProfileScripts);

    // Jalankan ULANG setiap kali navigasi Livewire berhasil
    document.addEventListener('livewire:navigated', initEditProfileScripts);
    </script>
@stop
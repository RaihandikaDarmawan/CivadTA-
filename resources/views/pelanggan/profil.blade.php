@extends('layouts.customer_premium')

@section('title', 'Profil Saya')

@section('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    let cropper;
    function initCropper(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const image = document.getElementById('image-to-crop');
                image.src = e.target.result;
                document.getElementById('crop-modal').classList.remove('hidden');
                
                if (cropper) cropper.destroy();
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 2,
                    dragMode: 'move',
                    background: false,
                    autoCropArea: 1,
                });
            };
            reader.readAsDataURL(file);
        }
    }

    function applyCrop() {
        const canvas = cropper.getCroppedCanvas({
            width: 512,
            height: 512
        });
        const croppedData = canvas.toDataURL('image/png');
        document.getElementById('cropped_photo').value = croppedData;
        document.getElementById('avatar-preview').src = croppedData;
        document.getElementById('crop-modal').classList.add('hidden');
    }
</script>
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-8 md:py-16 px-6" x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }} }">
    <header class="mb-12 flex flex-col md:flex-row justify-between items-center md:items-end gap-6 text-center md:text-left">
        <div>
            <h1 class="text-[32px] md:text-[48px] font-black text-emerald-950 tracking-tighter mb-3 leading-none">Profil Saya</h1>
            <p class="text-emerald-900 font-bold text-[12px] md:text-[14px] uppercase tracking-[0.3em]">Informasi Pribadi</p>
        </div>
        <button @click="editing = !editing" 
                class="w-full md:w-auto px-10 py-5 rounded-2xl font-black uppercase tracking-widest text-[12px] transition-all duration-300 shadow-xl"
                :class="editing ? 'bg-rose-500 text-white' : 'bg-emerald-950 text-white'">
            <span x-text="editing ? 'Batal Edit' : 'Edit Profil'"></span>
        </button>
    </header>

    <div class="bg-white rounded-[40px] md:rounded-[60px] border-2 border-emerald-950 shadow-2xl overflow-hidden">
        
        <!-- View Mode -->
        <div x-show="!editing" class="p-8 md:p-20">
            <div class="flex flex-col md:flex-row gap-12 md:gap-20 items-center md:items-start">
                <div class="w-40 h-40 md:w-56 md:h-56 rounded-[40px] bg-emerald-50 border-4 border-emerald-950 shadow-2xl flex items-center justify-center overflow-hidden shrink-0 group">
                    @if($user->profile_photo)
                        <img src="{{ $user->profile_photo }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-emerald-950 font-black text-[60px] md:text-[80px] uppercase">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-y-10 gap-x-12 w-full">
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black text-emerald-800/40 uppercase tracking-[0.3em] mb-2">Nama Lengkap</p>
                        <p class="text-[20px] md:text-[24px] font-black text-emerald-950 tracking-tight leading-none">{{ $user->name }}</p>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black text-emerald-800/40 uppercase tracking-[0.3em] mb-2">Username</p>
                        <p class="text-[20px] md:text-[24px] font-black text-emerald-950 tracking-tight leading-none">{{ $user->username }}</p>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black text-emerald-800/40 uppercase tracking-[0.3em] mb-2">Email Aktif</p>
                        <p class="text-[18px] font-bold text-emerald-900">{{ $user->email }}</p>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black text-emerald-800/40 uppercase tracking-[0.3em] mb-2">Nomor Telepon</p>
                        <p class="text-[18px] font-bold text-emerald-900">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2 text-center md:text-left">
                        <p class="text-[10px] font-black text-emerald-800/40 uppercase tracking-[0.3em] mb-2">Alamat Pengiriman</p>
                        <p class="text-[16px] md:text-[18px] font-bold text-emerald-900 leading-relaxed max-w-xl mx-auto md:mx-0">{{ $user->address ?? 'Belum mengatur alamat.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Mode -->
        <div x-show="editing" class="p-8 md:p-20 bg-emerald-50/20" x-cloak>
            <form action="{{ url('/pelanggan/profil/update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($errors->any())
                <div class="mb-8 p-6 bg-red-50 border border-red-100 text-red-600 text-[13px] rounded-3xl font-bold animate-in fade-in slide-in-from-top-2">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <input type="hidden" name="cropped_photo" id="cropped_photo">
                
                <div class="flex flex-col md:flex-row gap-12 md:gap-20">
                    <div class="flex flex-col items-center gap-6 shrink-0">
                        <div class="relative group">
                            <div class="w-40 h-40 md:w-56 md:h-56 rounded-[40px] bg-white border-4 border-emerald-950 shadow-2xl flex items-center justify-center overflow-hidden">
                                <img id="avatar-preview" src="{{ $user->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=064e3b&color=fff' }}" class="w-full h-full object-cover">
                            </div>
                            <label class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-emerald-950 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-pointer shadow-xl hover:bg-emerald-800 transition-all whitespace-nowrap">
                                Ubah Foto
                                <input type="file" id="photo-input" name="profile_photo" class="hidden" accept="image/*" onchange="initCropper(event)">
                            </label>
                        </div>
                        <p class="text-[11px] text-emerald-600 font-black mt-4 uppercase tracking-widest">Klik tombol untuk ganti</p>
                    </div>

                    <div class="flex-1 space-y-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ $user->name }}" required
                                       class="w-full px-6 py-4 bg-white border-2 border-emerald-950 rounded-[20px] focus:outline-none font-bold text-emerald-950 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Email</label>
                                <input type="email" name="email" value="{{ $user->email }}" required
                                       class="w-full px-6 py-4 bg-white border-2 border-emerald-950 rounded-[20px] focus:outline-none font-bold text-emerald-950 transition-all">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Nomor Telepon</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required
                                   minlength="10" maxlength="13" pattern="[0-9]{10,13}" inputmode="numeric"
                                   oninvalid="this.setCustomValidity('pastikan nomor anda minimal 10-13 digit')"
                                   oninput="this.setCustomValidity(''); this.value = this.value.replace(/[^0-9]/g, '')"
                                   class="w-full px-6 py-4 bg-white border-2 border-emerald-950 rounded-[20px] focus:outline-none font-bold text-emerald-950 transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea name="address" rows="4" required
                                      class="w-full px-6 py-4 bg-white border-2 border-emerald-950 rounded-[24px] focus:outline-none font-bold text-emerald-950 transition-all resize-none">{{ $user->address }}</textarea>
                        </div>
                        
                        <div class="pt-6 border-t border-emerald-950/10">
                            <p class="text-[11px] font-black text-emerald-900/40 uppercase tracking-widest mb-6 italic text-center md:text-left">Kosongkan jika tidak ingin mengubah password</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-emerald-950 uppercase tracking-widest ml-1">Password Baru</label>
                                    <input type="password" name="password" class="w-full px-6 py-4 bg-white border-2 border-emerald-950/10 rounded-[20px] focus:border-emerald-950 focus:outline-none font-bold text-emerald-950 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-emerald-950 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="w-full px-6 py-4 bg-white border-2 border-emerald-950/10 rounded-[20px] focus:border-emerald-950 focus:outline-none font-bold text-emerald-950 transition-all">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-5 bg-emerald-950 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[14px] shadow-2xl hover:bg-emerald-800 hover:scale-[1.01] active:scale-95 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Crop Modal -->
<div id="crop-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6 md:p-12">
    <div class="absolute inset-0 bg-emerald-950/90 backdrop-blur-xl"></div>
    <div class="relative bg-white w-full max-w-2xl rounded-[40px] md:rounded-[60px] overflow-hidden shadow-2xl">
        <div class="p-8 border-b border-emerald-50">
            <h3 class="text-[20px] font-black text-emerald-950">Potong Foto Profil</h3>
        </div>
        <div class="p-8">
            <div class="max-h-[400px] overflow-hidden rounded-[32px]">
                <img id="image-to-crop" class="max-w-full">
            </div>
            <div class="flex gap-4 mt-8">
                <button onclick="document.getElementById('crop-modal').classList.add('hidden')" class="flex-1 py-4 bg-emerald-50 text-emerald-950 rounded-2xl font-black uppercase text-[12px]">Batal</button>
                <button onclick="applyCrop()" class="flex-1 py-4 bg-emerald-950 text-white rounded-2xl font-black uppercase text-[12px]">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .cropper-view-box, .cropper-face { border-radius: 40px; }
</style>
@endsection

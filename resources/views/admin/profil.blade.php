@extends('layouts.admin_premium')

@section('title', 'Profil Administrator')

@section('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    let cropper;
    function initCropperAdmin(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const image = document.getElementById('image-to-crop-admin');
                image.src = e.target.result;
                document.getElementById('crop-modal-admin').classList.remove('hidden');
                
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

    function applyCropAdmin() {
        const canvas = cropper.getCroppedCanvas({
            width: 512,
            height: 512
        });
        const croppedData = canvas.toDataURL('image/png');
        document.getElementById('cropped_photo_admin').value = croppedData;
        document.getElementById('avatar-preview-admin').src = croppedData;
        document.getElementById('crop-modal-admin').classList.add('hidden');
    }
</script>
@endsection

@section('header')
<div class="hidden md:flex items-center gap-3 text-[12px] font-black text-emerald-500 uppercase tracking-widest mb-2">
    <a href="{{ url('/admin/dashboard') }}" class="hover:text-white transition-colors">Admin</a>
    <span class="text-white/20">/</span>
    <span class="text-white">Profil</span>
</div>
<h2 class="text-base md:text-[28px] font-black text-white tracking-tighter leading-none truncate max-w-[180px] md:max-w-none">Identitas Admin</h2>
@endsection

@section('content')

<div class="max-w-4xl mx-auto" x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }} }">

    <!-- Action Bar -->
    <div class="mb-8 flex justify-end">
        <button 
            @click="editing = !editing" 
            class="px-10 py-5 rounded-[24px] font-black uppercase tracking-widest text-[12px] transition-all duration-300 shadow-2xl text-white"
            :class="editing 
                ? 'bg-red-500 hover:bg-red-600 shadow-red-500/30' 
                : 'bg-emerald-950 hover:bg-emerald-900 shadow-emerald-950/30'"
        >
            <span x-text="editing ? 'Batal Edit' : 'Ubah Informasi Profil'"></span>
        </button>
    </div>

    <div class="bg-white rounded-[48px] shadow-2xl overflow-hidden border border-emerald-50 animate-in fade-in slide-in-from-bottom-8 duration-700">
        
        <!-- View Mode -->
        <div x-show="!editing" class="p-8 sm:p-16">
            <div class="flex flex-col md:flex-row gap-16 items-center md:items-start">
                <div class="w-48 h-48 rounded-[40px] bg-emerald-950 shadow-2xl flex items-center justify-center overflow-hidden shrink-0 border-8 border-emerald-50">
                    @if($admin->profile_photo)
                        <img src="{{ $admin->profile_photo }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-white font-black text-[72px] uppercase">
                            {{ substr($admin->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-y-10 gap-x-12">
                    <div>
                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-[0.3em] mb-2">Nama Lengkap</p>
                        <p class="text-[20px] font-black text-emerald-950 tracking-tight">{{ $admin->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-[0.3em] mb-2">System Username</p>
                        <p class="text-[20px] font-black text-emerald-950 tracking-tight">@ {{ $admin->username }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-[0.3em] mb-2">Email Aktif</p>
                        <p class="text-[18px] font-bold text-emerald-900">{{ $admin->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-[0.3em] mb-2">Nomor Telepon</p>
                        <p class="text-[18px] font-bold text-emerald-900">{{ $admin->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-[0.3em] mb-2">Region Access</p>
                        <p class="text-[18px] font-bold text-emerald-900">{{ $admin->daerah ?? 'Global' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-[0.3em] mb-2">Alamat Lengkap</p>
                        <p class="text-[18px] font-bold text-emerald-900 leading-relaxed">{{ $admin->address ?? 'Belum mengatur alamat.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Mode -->
        <div x-show="editing" x-cloak>
            <form action="{{ url('/admin/profil/update') }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-12">
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
                <input type="hidden" name="cropped_photo" id="cropped_photo_admin">

                <div class="flex flex-col md:flex-row gap-12 items-start">
                    <!-- Photo Upload -->
                    <div class="w-full md:w-1/3 flex flex-col items-center">
                        <div class="relative group cursor-pointer" onclick="document.getElementById('photo-input-admin').click()">
                            <div class="w-48 h-48 rounded-[40px] bg-emerald-950 border-4 border-emerald-950 shadow-2xl flex items-center justify-center overflow-hidden transition-all duration-500 group-hover:scale-105 relative">
                                @if($admin->profile_photo)
                                    <img id="avatar-preview-admin" src="{{ $admin->profile_photo }}" class="w-full h-full object-cover">
                                @else
                                    <div id="avatar-placeholder-admin" class="text-white font-black text-[72px] uppercase">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" /></svg>
                                </div>
                            </div>
                            <input type="file" id="photo-input-admin" name="profile_photo" class="hidden" accept="image/*" onchange="initCropperAdmin(event)">
                        </div>
                        <p class="text-[11px] text-emerald-600 font-black mt-4 uppercase tracking-widest">Ganti Photo Profile</p>
                    </div>

                    <!-- Form Section -->
                    <div class="w-full md:w-2/3 grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-black text-emerald-900 uppercase tracking-[0.2em] mb-3 ml-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $admin->name }}" required
                                   class="w-full px-6 py-4 bg-emerald-50 border-2 border-emerald-950 rounded-[20px] focus:bg-white focus:outline-none font-bold text-emerald-950 transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-emerald-900 uppercase tracking-[0.2em] mb-3 ml-2">Email</label>
                            <input type="email" name="email" value="{{ $admin->email }}"
                                   class="w-full px-6 py-4 bg-emerald-50 border-2 border-emerald-950 rounded-[20px] focus:bg-white focus:outline-none font-bold text-emerald-950 transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-emerald-900 uppercase tracking-[0.2em] mb-3 ml-2">Nomor Telepon</label>
                            <input type="tel" name="phone" value="{{ old('phone', $admin->phone) }}" required
                                   minlength="10" maxlength="13" pattern="[0-9]{10,13}" inputmode="numeric"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   class="w-full px-6 py-4 bg-emerald-50 border-2 border-emerald-950 rounded-[20px] focus:bg-white focus:outline-none font-bold text-emerald-950 transition-all">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-black text-emerald-900 uppercase tracking-[0.2em] mb-3 ml-2">Access Region</label>
                            <input type="text" name="daerah" value="{{ $admin->daerah }}"
                                   class="w-full px-6 py-4 bg-emerald-50 border-2 border-emerald-950 rounded-[20px] focus:bg-white focus:outline-none font-bold text-emerald-950 transition-all">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-black text-emerald-900 uppercase tracking-[0.2em] mb-3 ml-2">Alamat Lengkap</label>
                            <textarea name="address" rows="3"
                                      class="w-full px-6 py-4 bg-emerald-50 border-2 border-emerald-950 rounded-[24px] focus:bg-white focus:outline-none font-bold text-emerald-950 transition-all resize-none">{{ $admin->address }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex justify-end gap-4">
                    <button type="button" @click="editing = false" class="px-10 py-5 bg-emerald-50 text-emerald-900 rounded-[24px] font-black uppercase tracking-widest text-[12px] hover:bg-emerald-100 transition-all">
                        Batalkan
                    </button>
                    <button type="submit" class="px-10 py-5 bg-emerald-950 text-white rounded-[24px] font-black uppercase tracking-widest text-[12px] hover:bg-emerald-800 transition-all shadow-xl shadow-emerald-950/20 transform hover:-translate-y-1 active:translate-y-0">
                        Update Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Crop Modal -->
<div id="crop-modal-admin" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-emerald-950/90 backdrop-blur-sm hidden animate-in fade-in duration-300">
    <div class="bg-white rounded-[48px] w-full max-w-2xl overflow-hidden shadow-2xl border-4 border-emerald-950">
        <div class="p-8 border-b border-emerald-50 flex justify-between items-center">
            <h3 class="text-emerald-950 font-black text-[20px] tracking-tight">Sesuaikan Foto Admin</h3>
            <button onclick="document.getElementById('crop-modal-admin').classList.add('hidden')" class="text-emerald-300 hover:text-emerald-950 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-8">
            <div class="max-h-[400px] overflow-hidden rounded-3xl bg-emerald-50 border-2 border-emerald-100">
                <img id="image-to-crop-admin" class="max-w-full">
            </div>
        </div>
        <div class="p-8 bg-emerald-50 flex justify-end gap-4">
            <button onclick="document.getElementById('crop-modal-admin').classList.add('hidden')" class="px-8 py-4 text-emerald-900 font-black uppercase tracking-widest text-[11px]">Batal</button>
            <button onclick="applyCropAdmin()" class="px-10 py-4 bg-emerald-950 text-white rounded-2xl font-black uppercase tracking-widest text-[11px] shadow-lg shadow-emerald-950/20">Potong & Simpan</button>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .cropper-view-box, .cropper-face { border-radius: 40px; }
</style>
@endsection

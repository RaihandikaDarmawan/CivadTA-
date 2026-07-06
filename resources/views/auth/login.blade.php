<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Arya Duta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac',
                            400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d',
                            800: '#166534', 900: '#14532d', 950: '#052e16',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .input-premium {
            background: #f0fdf4;
            border: 2px solid #052e16; /* Permanent Border as requested earlier */
            transition: all 0.3s ease;
        }
        .input-premium:focus {
            background: white;
            box-shadow: 0 0 0 4px rgba(5, 46, 22, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="min-h-screen flex text-emerald-950 overflow-x-hidden bg-white">

    <!-- Left Panel: Large Visual Section -->
    <div class="hidden lg:flex lg:w-3/5 relative overflow-hidden bg-emerald-950">
        <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?q=80&w=2028&auto=format&fit=crop" 
             class="absolute inset-0 w-full h-full object-cover opacity-25 mix-blend-luminosity" alt="Library">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-950 via-emerald-950/80 to-transparent"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-[120px]"></div>

        <div class="relative z-10 flex flex-col justify-between p-20 w-full h-full">
            <div class="flex items-center gap-5">
                <div class="bg-white p-2.5 rounded-2xl shadow-2xl border border-emerald-800/30">
                    <img src="{{ asset('logo.jpg') }}" alt="Logo AD" class="h-12 w-auto">
                </div>
                <div class="flex flex-col">
                    <span class="text-white font-black text-3xl tracking-tighter leading-none">CIVAD</span>
                    <span class="text-white/60 font-medium text-xs tracking-normal mt-1">CV. Arya Duta Tangerang</span>
                </div>
            </div>

            <div class="max-w-xl">
                <h2 class="text-6xl font-black text-white leading-[1.1] tracking-tighter mb-10 font-serif">
                    Gerbang Menuju Ilmu Pengetahuan Tanpa Batas.
                </h2>
            </div>

            <div class="flex items-center gap-16">
                <div>
                    <span class="text-white font-black text-3xl tracking-tight block">25+</span>
                    <span class="text-emerald-400 font-bold text-[10px] uppercase tracking-widest opacity-80">Tahun Melayani</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel: Login Form -->
    <div class="w-full lg:w-2/5 flex flex-col justify-center items-center p-8 lg:p-20 relative bg-white">
        <div class="w-full max-w-[440px]">
            <!-- Quick Back Navigation -->
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 mb-10 px-6 py-3 bg-emerald-50 text-emerald-950 rounded-full text-[12px] font-black uppercase tracking-widest shadow-sm hover:bg-emerald-950 hover:text-white transition-all active:scale-95 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4 group-hover:-translate-x-1 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Beranda
            </a>

            <div class="mb-12">
                <h1 class="text-4xl font-black text-emerald-950 tracking-tight mb-3">Selamat Datang</h1>
                <p class="text-emerald-950 font-medium opacity-70">Masuk ke akun pengguna anda.</p>
            </div>

            <!-- Inline Error Alert Box displayed above the form -->
            @if(session('error') || $errors->any())
            <div class="mb-8 p-5 bg-red-50 border-2 border-red-500 text-red-600 text-[13px] rounded-[28px] font-bold animate-in fade-in slide-in-from-top-2">
                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 bg-red-100 text-red-600 rounded-full flex items-center justify-center shrink-0 mt-0.5 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        @if(session('error'))
                            <p>{{ session('error') }}</p>
                        @endif
                        @if($errors->any())
                            <ul class="list-disc pl-4 space-y-1 text-red-600">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[13px] rounded-2xl font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="role" id="login-role" value="pelanggan">

                <div class="space-y-2">
                    <label class="text-[12px] font-black text-emerald-950 uppercase tracking-[0.2em] ml-2 block mb-3">Login Sebagai</label>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Column 1: Admin -->
                        <div id="btn-role-admin" onclick="selectRole('admin')" class="flex flex-col items-center justify-center p-5 rounded-3xl border-2 cursor-pointer transition-all duration-300 bg-white border-emerald-100 text-emerald-900/40 hover:border-emerald-950/20">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-950 rounded-xl flex items-center justify-center mb-2 opacity-40">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286zm0 13.036h.008v.008H12v-.008z" /></svg>
                            </div>
                            <span class="text-[14px] font-black tracking-tight">Admin</span>
                        </div>
                        <!-- Column 2: Pelanggan -->
                        <div id="btn-role-pelanggan" onclick="selectRole('pelanggan')" class="flex flex-col items-center justify-center p-5 rounded-3xl border-2 cursor-pointer transition-all duration-300 bg-emerald-50 border-emerald-950 text-emerald-950 shadow-md">
                            <div class="w-10 h-10 bg-emerald-950 text-white rounded-xl flex items-center justify-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                            </div>
                            <span class="text-[14px] font-black tracking-tight">Pelanggan</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[12px] font-black text-emerald-950 uppercase tracking-[0.2em] ml-2">Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-emerald-950/30 group-focus-within:text-emerald-950 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </div>
                        <input type="text" name="username" placeholder="Masukkan username" required class="w-full pl-16 pr-6 py-5 input-premium rounded-[28px] text-[16px] font-bold text-emerald-950">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-2">
                        <label class="text-[12px] font-black text-emerald-950 uppercase tracking-[0.2em]">Kata Sandi</label>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-emerald-950/30 group-focus-within:text-emerald-950 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                        </div>
                        <input type="password" id="password" name="password" placeholder="••••••••" required class="w-full pl-16 pr-16 py-5 input-premium rounded-[28px] text-[16px] font-bold text-emerald-950">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-6 flex items-center text-emerald-950/30">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </button>
                    </div>
                    <div class="flex justify-end mt-2 px-2">
                        <a href="{{ route('password.request') }}" class="text-[13px] font-bold text-emerald-950/60 hover:text-emerald-950 transition-colors">Lupa Password?</a>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-950 text-white py-6 rounded-[28px] font-black text-[18px] shadow-2xl shadow-emerald-950/30 hover:bg-emerald-900 transition-all duration-500 active:scale-[0.97] flex items-center justify-center gap-4 group">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-12 text-center">
                <p class="text-[15px] text-emerald-950 font-bold opacity-60">
                    Belum bergabung? 
                    <a href="{{ url('/register') }}" class="text-emerald-950 font-black hover:underline underline-offset-8 decoration-emerald-300 ml-2">
                        Daftar Gratis
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pass.type === 'password') {
                pass.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />';
            } else {
                pass.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
            }
        }
        function selectRole(role) {
            document.getElementById('login-role').value = role;
            
            const btnAdmin = document.getElementById('btn-role-admin');
            const btnPelanggan = document.getElementById('btn-role-pelanggan');
            
            if (role === 'admin') {
                btnAdmin.className = "flex flex-col items-center justify-center p-5 rounded-3xl border-2 cursor-pointer transition-all duration-300 bg-emerald-50 border-emerald-950 text-emerald-950 shadow-md";
                btnAdmin.querySelector('div').className = "w-10 h-10 bg-emerald-950 text-white rounded-xl flex items-center justify-center mb-2";
                btnAdmin.querySelector('div').classList.remove('opacity-40');
                
                btnPelanggan.className = "flex flex-col items-center justify-center p-5 rounded-3xl border-2 cursor-pointer transition-all duration-300 bg-white border-emerald-100 text-emerald-900/40 hover:border-emerald-950/20";
                btnPelanggan.querySelector('div').className = "w-10 h-10 bg-emerald-50 text-emerald-950 rounded-xl flex items-center justify-center mb-2 opacity-40";
            } else {
                btnPelanggan.className = "flex flex-col items-center justify-center p-5 rounded-3xl border-2 cursor-pointer transition-all duration-300 bg-emerald-50 border-emerald-950 text-emerald-950 shadow-md";
                btnPelanggan.querySelector('div').className = "w-10 h-10 bg-emerald-950 text-white rounded-xl flex items-center justify-center mb-2";
                btnPelanggan.querySelector('div').classList.remove('opacity-40');
                
                btnAdmin.className = "flex flex-col items-center justify-center p-5 rounded-3xl border-2 cursor-pointer transition-all duration-300 bg-white border-emerald-100 text-emerald-900/40 hover:border-emerald-950/20";
                btnAdmin.querySelector('div').className = "w-10 h-10 bg-emerald-50 text-emerald-950 rounded-xl flex items-center justify-center mb-2 opacity-40";
            }
        }
    </script>
</body>
</html>

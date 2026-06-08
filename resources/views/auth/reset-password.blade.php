<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atur Ulang Password - Arya Duta</title>
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
            border: 2px solid #052e16;
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

    <!-- Left Panel -->
    <div class="hidden lg:flex lg:w-3/5 relative overflow-hidden bg-emerald-950">
        <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=2028&auto=format&fit=crop" 
             class="absolute inset-0 w-full h-full object-cover opacity-25 mix-blend-luminosity" alt="Library">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-950 via-emerald-950/80 to-transparent"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-[120px]"></div>

        <div class="relative z-10 flex flex-col justify-between p-20 w-full h-full">
            <div class="flex items-center gap-5">
                <div class="bg-white p-2.5 rounded-2xl shadow-2xl border border-emerald-800/30">
                    <img src="{{ asset('logo.jpg') }}" alt="Logo AD" class="h-12 w-auto">
                </div>
                <div class="flex flex-col">
                    <span class="text-white font-black text-3xl tracking-tighter leading-none">Arya Duta</span>
                    <span class="text-emerald-400 font-bold text-[10px] uppercase tracking-[0.4em] mt-1.5">Official Platform</span>
                </div>
            </div>

            <div class="max-w-xl">
                <h2 class="text-6xl font-black text-white leading-[1.1] tracking-tighter mb-10 font-serif">
                    Buat Password <br><span class="italic text-emerald-400">Baru</span> Anda.
                </h2>
                <p class="text-xl text-emerald-100/80 font-medium leading-relaxed">
                    Pastikan kata sandi baru Anda kuat dan mudah diingat untuk keamanan akun yang maksimal.
                </p>
            </div>
            
            <div class="text-white/40 text-sm font-bold tracking-widest uppercase">
                © 2026 Arya Duta Bookstore — Premium Education
            </div>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="w-full lg:w-2/5 flex flex-col justify-center items-center p-8 lg:p-20 relative bg-white">
        <div class="w-full max-w-[440px]">
            <div class="mb-12">
                <h1 class="text-4xl font-black text-emerald-950 tracking-tight mb-3">Atur Ulang Password</h1>
                <p class="text-emerald-950 font-medium opacity-70">Silakan masukkan password baru Anda di bawah ini.</p>
            </div>

            @if($errors->any())
            <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-600 text-[13px] rounded-2xl font-bold animate-in fade-in slide-in-from-top-2">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="space-y-2">
                    <label class="text-[12px] font-black text-emerald-950 uppercase tracking-[0.2em] ml-2">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-emerald-950/30 group-focus-within:text-emerald-950 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                        </div>
                        <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly class="w-full pl-16 pr-6 py-5 bg-emerald-50/50 border-2 border-emerald-950/10 rounded-[28px] text-[16px] font-bold text-emerald-950/50 cursor-not-allowed">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[12px] font-black text-emerald-950 uppercase tracking-[0.2em] ml-2">Password Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-emerald-950/30 group-focus-within:text-emerald-950 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                        </div>
                        <input type="password" name="password" placeholder="••••••••" required class="w-full pl-16 pr-6 py-5 input-premium rounded-[28px] text-[16px] font-bold text-emerald-950">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[12px] font-black text-emerald-950 uppercase tracking-[0.2em] ml-2">Konfirmasi Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-emerald-950/30 group-focus-within:text-emerald-950 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        </div>
                        <input type="password" name="password_confirmation" placeholder="••••••••" required class="w-full pl-16 pr-6 py-5 input-premium rounded-[28px] text-[16px] font-bold text-emerald-950">
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-950 text-white py-6 rounded-[28px] font-black text-[18px] shadow-2xl shadow-emerald-950/30 hover:bg-emerald-900 transition-all duration-500 active:scale-[0.97] flex items-center justify-center gap-4 group">
                    Perbarui Password
                </button>
            </form>
        </div>
    </div>
</body>
</html>

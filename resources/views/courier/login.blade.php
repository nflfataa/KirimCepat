<x-layout
    :landing="true"
    title="KirimCepat"
    description="Portal autentikasi kurir KirimCepat untuk pembaruan status pengiriman real-time."
>
    <div class="min-h-screen lg:h-screen w-full bg-white grid grid-cols-1 lg:grid-cols-2 overflow-x-hidden lg:overflow-hidden">
        
        <!-- Left Side: 50% Truck Image Hero (Flush Box, No Border, Clean & Balanced) -->
        <div class="relative w-full h-[320px] sm:h-[400px] lg:h-full bg-neutral-900 flex flex-col justify-between p-6 sm:p-10 lg:p-12 xl:p-16">
            <!-- Background Truck Image -->
            <img
                src="{{ asset('images/fleet-unsplash.jpg') }}"
                alt="Armada Truk KirimCepat di Jalan Raya"
                class="absolute inset-0 w-full h-full object-cover object-center"
            />
            <!-- Contrast overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-black/50" aria-hidden="true"></div>

            <!-- Top Eyebrow with Line -->
            <div class="relative z-10 flex items-center gap-3">
                <span class="text-xs font-bold tracking-[0.22em] uppercase text-white/90">
                    Armada Ekspedisi
                </span>
                <div class="h-[1px] flex-1 bg-white/30" aria-hidden="true"></div>
            </div>

            <!-- Bottom Hero Quote / Typography -->
            <div class="relative z-10 mt-auto pt-6">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl xl:text-[2.6rem] font-black text-white leading-[1.08] tracking-tight">
                    Antar Cepat,<br>Tepat &amp;<br>Terpercaya
                </h2>
                <p class="text-xs sm:text-sm text-neutral-200 mt-3 leading-relaxed max-w-md font-normal">
                    Pembaruan status real-time, transparansi alur paket, dan dedikasi armada kurir menjangkau seluruh Jawa Timur.
                </p>
            </div>
        </div>

        <!-- Right Side: 50% Login Form (White Background, Balanced Spacing, No Scroll) -->
        <div class="relative w-full h-full bg-white flex flex-col justify-between p-6 sm:p-10 lg:p-12 xl:p-16 lg:overflow-hidden">
            
            <!-- Top Header: Centered KirimCepat Logo (Naik ke Atas) & Right Navigation -->
            <div class="relative flex items-center justify-center w-full min-h-[32px]">
                <!-- Logo Centered horizontally in 50% column, level with top of image -->
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 group" aria-label="KirimCepat Beranda">
                    <svg class="w-6 h-6 text-black shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                    </svg>
                    <span class="text-xs font-black tracking-[0.24em] uppercase text-black">KirimCepat</span>
                </a>

                <!-- Top Navigation (Right-aligned Link) -->
                <a href="{{ route('home') }}" class="absolute right-0 top-1/2 -translate-y-1/2 text-xs font-medium text-neutral-400 hover:text-black transition flex items-center gap-1">
                    <span>Beranda</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <!-- Form Body: Centered in Right 50% viewport without scroll -->
            <div class="my-auto py-2 sm:py-4 max-w-sm w-full mx-auto">
                
                <!-- Heading -->
                <div class="text-center mb-5 sm:mb-6">
                    <span class="text-[11px] font-bold tracking-widest uppercase text-neutral-400 block mb-1">
                        Panel kurir
                    </span>
                    <h1 class="text-2xl sm:text-3xl lg:text-[2.2rem] font-black tracking-tight text-neutral-900 leading-tight">
                        Welcome Back
                    </h1>
                    <p class="text-neutral-500 text-xs sm:text-sm mt-1.5 max-w-xs mx-auto">
                        Masukkan email dan kata sandi untuk masuk ke panel kurir.
                    </p>
                </div>

                <form method="POST" action="{{ route('kurir.masuk.store') }}" class="space-y-3.5">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold text-neutral-700 mb-1.5">
                            Email
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="username"
                            value="{{ old('email') }}"
                            placeholder="kurir@kirimcepat.test"
                            required
                            class="w-full min-h-[46px] px-4 rounded-xl border @error('email') border-red-500 bg-red-50/40 @else border-neutral-200 bg-neutral-50/70 @enderror focus:bg-white focus:border-black focus:ring-1 focus:ring-black outline-none transition text-sm text-neutral-900 placeholder:text-neutral-400"
                        />
                        @error('email')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-neutral-700 mb-1.5">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi"
                                required
                                class="w-full min-h-[46px] px-4 pr-11 rounded-xl border @error('password') border-red-500 bg-red-50/40 @else border-neutral-200 bg-neutral-50/70 @enderror focus:bg-white focus:border-black focus:ring-1 focus:ring-black outline-none transition text-sm text-neutral-900 placeholder:text-neutral-400"
                            />
                            <button
                                type="button"
                                id="toggle-password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-black transition p-1 cursor-pointer"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                            >
                                <svg id="eye-open" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg id="eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between text-xs pt-0.5">
                        <label for="remember" class="flex items-center gap-2 cursor-pointer select-none text-neutral-600 hover:text-black">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                value="1"
                                class="w-3.5 h-3.5 rounded text-black focus:ring-black border-neutral-300 cursor-pointer"
                            />
                            <span>Remember me</span>
                        </label>

                        <span class="text-neutral-400 hover:text-black transition cursor-help" title="Hubungi admin operasional untuk bantuan akun">
                            Lupa sandi?
                        </span>
                    </div>

                    <button
                        type="submit"
                        class="w-full min-h-[46px] bg-black text-white font-bold rounded-xl hover:bg-neutral-800 transition shadow-sm mt-2 flex items-center justify-center text-sm cursor-pointer"
                    >
                        Masuk
                    </button>
                </form>

                @if (app()->environment('local'))
                    <div class="mt-3.5 p-3 rounded-xl bg-neutral-50 border border-neutral-200 text-[11px] text-neutral-600 space-y-0.5">
                        <strong class="font-bold text-black block mb-0.5">Akun demo pengembangan</strong>
                        <div>Email: <span class="font-mono text-black font-semibold">kurir@kirimcepat.test</span></div>
                        <div>Kata sandi: <span class="font-mono text-black font-semibold">password</span></div>
                    </div>
                @endif
            </div>

            <!-- Footer note -->
            <div class="text-center text-xs text-neutral-400 pt-3 border-t border-neutral-100">
                Bukan kurir operasional? <a href="{{ route('home') }}" class="font-semibold text-black hover:underline">Kembali ke Beranda</a>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('toggle-password');
            const passInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (toggleBtn && passInput && eyeOpen && eyeClosed) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = passInput.type === 'password';
                    passInput.type = isPassword ? 'text' : 'password';
                    eyeOpen.classList.toggle('hidden', isPassword);
                    eyeClosed.classList.toggle('hidden', !isPassword);
                });
            }
        });
    </script>
</x-layout>

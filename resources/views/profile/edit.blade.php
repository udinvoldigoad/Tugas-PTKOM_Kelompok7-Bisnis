<x-app-layout :hideNavigation="true">
    <div class="min-h-[100dvh] bg-[#FAF9F5] flex font-sans">

        <!-- Outer Application Window Frame -->
        <div x-data class="profile-shell">

            <!-- ================= LEFT SIDEBAR ================= -->
            <x-profile-sidebar active="profil" />

            <!-- ================= MAIN CONTENT AREA ================= -->
            <main class="min-w-0 flex-1 bg-[#FAF9F5] px-5 py-6 sm:px-8 lg:px-9 lg:py-7 overflow-y-auto">
                <div>
                    <!-- ================= TOP HEADER ================= -->
                    <header class="flex items-center justify-between mb-7">
                        <div>
                            <h1
                                class="text-3xl sm:text-4xl font-bold font-mono tracking-wider text-[#1E1B18]"
                                >
                                Profil
                            </h1>
                            <p
                                class="text-xs sm:text-sm font-mono text-[#524F49] tracking-wide mt-1"
                                >
                                Kelola Informasi Akun Anda
                            </p>
                        </div>

                        <x-user-summary-chip :user="$user" />
                    </header>

                    <!-- Alert Notification (If Saved) -->
                    @if (session('status') === 'profile-updated')
                        <div class="mb-5 bg-[#DCFCE7] border border-[#86EFAC] text-[#15803D] px-4 py-2.5 rounded-xl font-mono text-xs flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm">✓</span>
                                <span>Informasi profil akun Anda berhasil disimpan!</span>
                            </div>
                            <button @click="$el.parentElement.remove()" class="text-[#15803D] hover:text-[#166534] font-bold">✕</button>
                        </div>
                    @endif

                    @error('avatar')
                        <p role="alert" class="mb-4 text-red-600">{{ $message }}</p>
                    @enderror
                    <!-- ================= TAB: PROFIL / DASHBOARD (EXACT MOCKUP) ================= -->
                    <div class="space-y-6">

                        <!-- CARD 1: TOP PROFILE SUMMARY CARD -->
                        <div class="min-h-[120px] bg-white rounded-lg border border-[#D5D1C9] px-5 py-3 sm:px-6 flex flex-col md:flex-row md:items-center justify-between gap-5">

                            <!-- Left: Big Avatar + User Identity -->
                            <div class="flex items-center gap-5">

                                <!-- Large Circle Avatar with Camera Button -->
                                <div class="relative shrink-0">
                                    <div class="w-24 h-24 rounded-full bg-[#C2A373] flex items-center justify-center overflow-hidden">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            <!-- Crisp White User Silhouette Matching Mockup -->
                                            <svg class="w-[72px] h-[72px] text-white translate-y-2" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Camera Trigger Overlay Button -->
                                    <label for="avatar-input" class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-white border border-[#2B2823] flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-100 transition transform hover:scale-110" title="Ubah Foto Profil">
                                        <svg class="w-4 h-4 text-[#1E1B18]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                            <circle cx="12" cy="13" r="4"/>
                                        </svg>
                                     </label>

                                    @if($user->avatar)
                                    <!-- Hapus Foto Button -->
                                    <form method="POST" action="{{ route('profile.avatar.destroy') }}" class="absolute top-0 right-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            title="Hapus Foto Profil"
                                            onclick="return confirm('Hapus foto profil?')"
                                            class="w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 border-2 border-white flex items-center justify-center shadow-md transition cursor-pointer">
                                            <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>

                                <!-- User Name, Subtitle, Badges -->
                                <div class="flex flex-col">
                                    <h2 class="text-xl sm:text-2xl font-bold font-mono text-[#1E1B18] tracking-wide">
                                        {{ $user->name ?? 'Irfan' }}
                                    </h2>
                                    <p class="text-xs sm:text-sm font-mono text-[#524F49] mt-0.5">
                                        {{ strtolower($user->role ?? 'kasir') }} Shift {{ $user->shift ?? '1' }}
                                    </p>

                                    <!-- Badges Row -->
                                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                                        <!-- Role Pill (Tan/Camel) -->
                                        <span class="bg-[#D8B888] text-white text-[10px] font-mono font-medium px-2 py-1 rounded inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                            {{ $user->role ?? 'Kasir' }}
                                        </span>

                                        <!-- Online Status Pill (Light Green) -->
                                        <span class="bg-[#CDEFC7] text-[#287E36] text-[10px] font-mono font-medium px-2 py-1 rounded inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#38A34A]"></span>
                                            Online
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Join Date & Last Login (Matching Mockup) -->
                            <div class="md:min-w-[188px] border-t md:border-t-0 md:border-l border-[#D5D1C9] pt-4 md:py-1 md:pl-5 flex flex-col justify-center gap-3">
                                <div>
                                    <span class="text-xs font-mono text-[#524F49] block">Bergabung sejak</span>
                                    <div class="text-xs sm:text-sm font-mono font-medium text-[#1E1B18] mt-1 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#524F49]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        <span>{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '12 Agustus 2022' }}</span>
                                    </div>
                                </div>

                                <div>
                                    <span class="text-xs font-mono text-[#524F49] block">terakhir Login</span>
                                    <div class="text-xs sm:text-sm font-mono font-medium text-[#1E1B18] mt-1 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#524F49]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                        <span>{{ $user->last_login_at ? $user->last_login_at->translatedFormat('d F Y, H:i') : 'Belum tercatat' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BOTTOM ROW: DUAL CARDS (60% / 40%) -->
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                            <!-- CARD 2: "Informasi pengguna" (Left Column) -->
                            <div class="lg:col-span-7 bg-white rounded-lg border border-[#D5D1C9] px-6 py-5">
                                <h3 class="text-lg font-bold font-mono text-[#1E1B18]">Informasi pengguna</h3>
                                <p class="text-xs font-mono text-[#66635C] mt-0.5 mb-4">Informasi akun anda</p>

                                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    @method('patch')

                                    <!-- Hidden Avatar File Input (Triggered by camera icon) -->
                                    <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/jpeg,image/png,image/webp" onchange="this.form.requestSubmit()">

                                    <!-- Row 1: Nama Lengkap -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                                        <label for="full_name" class="w-32 shrink-0 text-xs sm:text-sm font-mono text-[#1E1B18]">
                                            Nama Lengkap
                                        </label>
                                        <div class="flex-1">
                                            <input
                                                type="text"
                                                id="full_name"
                                                name="full_name"
                                                value="{{ old('full_name', $user->full_name ?? $user->name) }}"
                                                class="w-full h-7 border border-[#C9C6BF] rounded px-2.5 py-1 text-xs font-mono text-[#1E1B18] bg-white focus:outline-none focus:ring-2 focus:ring-[#A88B5D]/50 focus:border-[#A88B5D]">
                                            @error('full_name')
                                                <p class="text-red-500 font-mono text-[11px] mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Row 2: Role (Readonly) -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                                        <label class="w-32 shrink-0 text-xs sm:text-sm font-mono text-[#1E1B18]">
                                            Role
                                        </label>
                                        <div class="flex-1">
                                            <input
                                                type="text"
                                                readonly
                                                value="{{ $user->role ?? 'Kasir' }}"
                                                class="w-full h-7 border border-[#C9C6BF] rounded px-2.5 py-1 text-xs font-mono text-[#5C5A55] bg-[#EAE8E2] cursor-not-allowed select-none">
                                        </div>
                                    </div>

                                    <!-- Row 3: Shift (Readonly) -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                                        <label class="w-32 shrink-0 text-xs sm:text-sm font-mono text-[#1E1B18]">
                                            Shift
                                        </label>
                                        <div class="flex-1">
                                            <input
                                                type="text"
                                                readonly
                                                value="{{ $user->shift ?? '1' }}"
                                                class="w-full h-7 border border-[#C9C6BF] rounded px-2.5 py-1 text-xs font-mono text-[#5C5A55] bg-[#EAE8E2] cursor-not-allowed select-none">
                                        </div>
                                    </div>

                                    <!-- Row 4: Email -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                                        <label for="email" class="w-32 shrink-0 text-xs sm:text-sm font-mono text-[#1E1B18]">
                                            Email
                                        </label>
                                        <div class="flex-1">
                                            <input
                                                type="email"
                                                id="email"
                                                name="email"
                                                value="{{ old('email', $user->email) }}"
                                                class="w-full h-7 border border-[#C9C6BF] rounded px-2.5 py-1 text-xs font-mono text-[#1E1B18] bg-white focus:outline-none focus:ring-2 focus:ring-[#A88B5D]/50 focus:border-[#A88B5D]">
                                            @error('email')
                                                <p class="text-red-500 font-mono text-[11px] mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Row 5: No Telp -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                                        <label for="phone" class="w-32 shrink-0 text-xs sm:text-sm font-mono text-[#1E1B18]">
                                            No Telp
                                        </label>
                                        <div class="flex-1">
                                            <input
                                                type="text"
                                                id="phone"
                                                name="phone"
                                                value="{{ old('phone', $user->phone ?? '') }}"
                                                class="w-full h-7 border border-[#C9C6BF] rounded px-2.5 py-1 text-xs font-mono text-[#1E1B18] bg-white focus:outline-none focus:ring-2 focus:ring-[#A88B5D]/50 focus:border-[#A88B5D]">
                                            @error('phone')
                                                <p class="text-red-500 font-mono text-[11px] mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button (Floppy Disk Icon) -->
                                    <div class="pt-1">
                                        <button
                                            type="submit"
                                            class="bg-[#A88B5D] hover:bg-[#967B4E] text-white text-xs font-mono px-3 py-2 rounded inline-flex items-center gap-2 transition-colors cursor-pointer">
                                            <!-- Floppy Disk Icon -->
                                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                                <polyline points="17 21 17 13 7 13 7 21"/>
                                                <polyline points="7 3 7 8 15 8"/>
                                            </svg>
                                            <span>simpan Perubahan</span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- CARD 3: "Informasi Tambahan" (Right Column) -->
                            <div class="lg:col-span-5 bg-white rounded-lg border border-[#D5D1C9] px-5 py-5 flex flex-col">
                                <h3 class="text-lg font-bold font-mono text-[#1E1B18]">Informasi Tambahan</h3>
                                <p class="text-xs font-mono text-[#66635C] mt-0.5 mb-3">Informasi ini digunakan untuk keperluan operasional</p>

                                <!-- Gray Rounded Container Inside (Matching Mockup) -->
                                <div class="bg-[#EAE8E2] rounded-lg px-4 py-3 flex flex-col gap-3">

                                    <!-- Row 1: Outlet -->
                                    <div class="grid grid-cols-[20px_96px_minmax(0,1fr)] items-center gap-x-2.5">
                                        <!-- Store Icon -->
                                        <svg class="w-5 h-5 text-[#423F39]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                            <polyline points="9 22 9 12 15 12 15 22"/>
                                        </svg>
                                        <span class="text-xs font-mono text-[#1E1B18]">Outlet</span>
                                        <span class="min-w-0 text-xs font-mono font-bold text-[#1E1B18]">
                                            {{ $user->outlet_name ?? 'Kafe Ridho' }}
                                        </span>
                                    </div>

                                    <!-- Row 2: Alamat Outlet -->
                                    <div class="grid grid-cols-[20px_96px_minmax(0,1fr)] items-center gap-x-2.5">
                                        <!-- Map Pin Icon -->
                                        <svg class="w-5 h-5 text-[#423F39]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                        <span class="text-xs font-mono text-[#1E1B18]">Alamat Outlet</span>
                                        <span class="min-w-0 text-[11px] leading-4 font-mono font-medium text-[#1E1B18]">
                                            {{ $user->outlet_address ?? 'Jl.way huwi no 5 lampung' }}
                                        </span>
                                    </div>

                                    <!-- Row 3: Jam Kerja -->
                                    <div class="grid grid-cols-[20px_96px_minmax(0,1fr)] items-center gap-x-2.5">
                                        <!-- Clock Icon -->
                                        <svg class="w-5 h-5 text-[#423F39]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                        <span class="text-xs font-mono text-[#1E1B18]">Jam Kerja</span>
                                        <span class="min-w-0 text-[11px] leading-4 font-mono font-medium text-[#1E1B18]">
                                            {{ $user->work_hours ?? '08:00 - 16:00 (Shift 1)' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-[#E2DFD7] flex-1 flex flex-col">
                                    <h4 class="text-sm font-bold font-mono text-[#1E1B18]">Akun &amp; Akses</h4>
                                    <p class="text-[11px] font-mono text-[#66635C] mt-0.5 mb-3">Ringkasan akses pengguna saat ini</p>

                                    <div class="mt-auto grid grid-cols-1 gap-2 sm:grid-cols-3">
                                        <div class="rounded-lg bg-[#F4F2ED] px-3 py-2.5">
                                            <span class="block text-[10px] font-mono text-[#78756F] mb-1">Peran</span>
                                            <strong class="block text-xs font-mono text-[#1E1B18]">{{ $user->role ?? 'Kasir' }}</strong>
                                        </div>
                                        <div class="rounded-lg bg-[#F4F2ED] px-3 py-2.5">
                                            <span class="block text-[10px] font-mono text-[#78756F] mb-1">Shift</span>
                                            <strong class="block text-xs font-mono text-[#1E1B18]">Shift {{ $user->shift ?? '1' }}</strong>
                                        </div>
                                        <div class="rounded-lg bg-[#F4F2ED] px-3 py-2.5">
                                            <span class="block text-[10px] font-mono text-[#78756F] mb-1">Penjualan Hari Ini</span>
                                            <strong class="block text-xs font-mono text-[#1E1B18]">
                                                Rp {{ number_format($totalPenjualanHariIni ?? 0, 0, ',', '.') }}
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </main>
        </div>
    </div>
</x-app-layout>

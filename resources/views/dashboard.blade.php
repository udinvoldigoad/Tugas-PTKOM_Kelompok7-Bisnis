<x-app-layout :hideNavigation="true">
    <div x-data="{
        currentTab: 'dashboard',
        cart: [],
        searchMenu: '',
        activeCategory: 'semua',
        addToCart(menu) {
            let existing = this.cart.find(item => item.id === menu.id);
            if (existing) {
                existing.qty++;
            } else {
                this.cart.push({
                    id: menu.id,
                    nama: menu.nama_menu,
                    harga: parseFloat(menu.harga),
                    qty: 1
                });
            }
        },
        removeFromCart(id) {
            this.cart = this.cart.filter(item => item.id !== id);
        },
        updateQty(id, delta) {
            let item = this.cart.find(i => i.id === id);
            if (item) {
                item.qty += delta;
                if (item.qty <= 0) {
                    this.removeFromCart(id);
                }
            }
        },
        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
        },
        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }
    }" class="min-h-screen bg-[#EDEAE3] p-3 sm:p-5 lg:p-6 flex font-sans">

        <!-- Outer Application Window Frame -->
        <div class="flex-1 w-full bg-[#FAF9F5] rounded-3xl border border-[#2B2823]/15 shadow-2xl overflow-hidden flex flex-col lg:flex-row">

            <!-- ================= LEFT SIDEBAR ================= -->
            <aside class="w-full lg:w-48 bg-[#D8B888] flex flex-row lg:flex-col items-center justify-between p-4 lg:py-6 lg:px-4 shrink-0 border-b lg:border-b-0 lg:border-r border-[#2B2823]/10">
                
                <!-- Logo + Brand Name Top -->
                <div class="flex items-center gap-3 mb-0 lg:mb-8">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-2.5" title="Kafe Ridho">
                        <img src="{{ asset('depan/logo.png') }}" alt="Logo Kafe Ridho" class="w-10 h-10 object-contain transition transform group-hover:scale-105 shrink-0">
                        <div class="hidden lg:flex flex-col leading-tight">
                            <span class="font-mono font-bold text-sm text-[#1E1B18] tracking-widest uppercase">Coffe</span>
                            <span class="font-mono font-bold text-sm text-[#1E1B18] tracking-widest uppercase">Ridho</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Menu Items -->
                <nav class="flex flex-row lg:flex-col items-center lg:items-stretch lg:flex-1 justify-around lg:justify-evenly gap-3 lg:gap-0 w-full">

                    <!-- 1. Dashboard -->
                    <button
                        @click="currentTab = 'dashboard'"
                        :class="currentTab === 'dashboard' ? 'bg-[#DE541E] text-white shadow-md' : 'bg-white/70 text-[#5D4E43] hover:bg-white'"
                        class="relative w-12 lg:w-full h-12 lg:h-auto lg:px-4 lg:py-5 rounded-2xl flex items-center justify-center lg:justify-start gap-3 cursor-pointer transition-all duration-200"
                        title="Dashboard">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <rect x="3" y="3" width="7.5" height="7.5" rx="2" />
                            <rect x="13.5" y="3" width="7.5" height="7.5" rx="2" />
                            <rect x="3" y="13.5" width="7.5" height="7.5" rx="2" />
                            <rect x="13.5" y="13.5" width="7.5" height="7.5" rx="2" />
                        </svg>
                        <span class="hidden lg:inline font-mono font-semibold text-sm">Dashboard</span>
                        <span x-show="currentTab === 'dashboard'" class="hidden lg:flex absolute -right-4.5 top-1/2 -translate-y-1/2 bg-[#DE541E] text-white w-5 h-7 rounded-r-md items-center justify-center text-[11px] font-bold z-20">›</span>
                    </button>

                    <!-- 2. Kasir -->
                    <button
                        @click="currentTab = 'kasir'"
                        :class="currentTab === 'kasir' ? 'bg-[#DE541E] text-white shadow-md' : 'bg-white/70 text-[#5D4E43] hover:bg-white'"
                        class="relative w-12 lg:w-full h-12 lg:h-auto lg:px-4 lg:py-5 rounded-2xl flex items-center justify-center lg:justify-start gap-3 cursor-pointer transition-all duration-200"
                        title="Kasir">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="10" width="16" height="11" rx="2" fill="currentColor" fill-opacity="0.15"/>
                            <path d="M7 6h10v4H7z"/>
                            <path d="M4 14h16"/>
                            <circle cx="8" cy="18" r="1" fill="currentColor"/>
                            <circle cx="12" cy="18" r="1" fill="currentColor"/>
                            <circle cx="16" cy="18" r="1" fill="currentColor"/>
                        </svg>
                        <span class="hidden lg:inline font-mono font-semibold text-sm">Cashier</span>
                        <span x-show="currentTab === 'kasir'" class="hidden lg:flex absolute -right-4.5 top-1/2 -translate-y-1/2 bg-[#DE541E] text-white w-5 h-7 rounded-r-md items-center justify-center text-[11px] font-bold z-20">›</span>
                    </button>

                    <!-- 3. Riwayat -->
                    <button
                        @click="currentTab = 'riwayat'"
                        :class="currentTab === 'riwayat' ? 'bg-[#DE541E] text-white shadow-md' : 'bg-white/70 text-[#5D4E43] hover:bg-white'"
                        class="relative w-12 lg:w-full h-12 lg:h-auto lg:px-4 lg:py-5 rounded-2xl flex items-center justify-center lg:justify-start gap-3 cursor-pointer transition-all duration-200"
                        title="Riwayat">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                            <path d="M12 7v5l3 3"/>
                        </svg>
                        <span class="hidden lg:inline font-mono font-semibold text-sm">Riwayat</span>
                        <span x-show="currentTab === 'riwayat'" class="hidden lg:flex absolute -right-4.5 top-1/2 -translate-y-1/2 bg-[#DE541E] text-white w-5 h-7 rounded-r-md items-center justify-center text-[11px] font-bold z-20">›</span>
                    </button>

                    <!-- 4. Profil -->
                    <button
                        @click="currentTab = 'profil'"
                        :class="currentTab === 'profil' ? 'bg-[#DE541E] text-white shadow-md' : 'bg-white/70 text-[#5D4E43] hover:bg-white'"
                        class="relative w-12 lg:w-full h-12 lg:h-auto lg:px-4 lg:py-5 rounded-2xl flex items-center justify-center lg:justify-start gap-3 cursor-pointer transition-all duration-200"
                        title="Profil">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span class="hidden lg:inline font-mono font-semibold text-sm">Profil</span>
                        <span x-show="currentTab === 'profil'" class="hidden lg:flex absolute -right-4.5 top-1/2 -translate-y-1/2 bg-[#DE541E] text-white w-5 h-7 rounded-r-md items-center justify-center text-[11px] font-bold z-20">›</span>
                    </button>
                </nav>

                <!-- Logout Bottom -->

                <div class="mt-0 lg:mt-6 w-full">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-12 lg:w-full h-12 lg:h-auto lg:px-4 lg:py-3 bg-[#FF6565] hover:bg-[#E25353] rounded-2xl flex items-center justify-center lg:justify-start gap-3 shadow-sm text-white transition-all duration-200 cursor-pointer"
                            title="Keluar / Logout">
                            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span class="hidden lg:inline font-mono font-semibold text-sm">Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- ================= MAIN CONTENT AREA ================= -->
            <main class="flex-1 bg-[#FAF9F5] p-5 sm:p-8 lg:p-10 flex flex-col justify-between overflow-y-auto">
                <div>
                    <!-- ================= TOP HEADER ================= -->
                    <header class="flex items-center justify-between mb-6 pb-2">
                        <div>
                            <h1 
                                class="text-3xl sm:text-4xl font-bold font-mono tracking-wider text-[#1E1B18]"
                                x-text="currentTab === 'kasir' ? 'Kasir Kafe' : (currentTab === 'riwayat' ? 'Riwayat Transaksi' : 'Profil')">
                                Profil
                            </h1>
                            <p 
                                class="text-xs sm:text-sm font-mono text-[#524F49] tracking-wide mt-1"
                                x-text="currentTab === 'kasir' ? 'Point of Sale & Pemesanan Kafe Ridho' : (currentTab === 'riwayat' ? 'Daftar Pembukuan Transaksi Kasir' : 'Kelola Informasi Akun Anda')">
                                Kelola Informasi Akun Anda
                            </p>
                        </div>

                        <!-- User Profile Pill (Top Right) -->
                        <div class="bg-[#EAE8E2] rounded-full py-1.5 px-4 flex items-center gap-3 border border-[#E0DED7]/60 shadow-xs">
                            <div class="w-8 h-8 rounded-full bg-[#5D4E43] flex items-center justify-center text-white font-bold text-xs shrink-0 overflow-hidden">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-5 h-5 text-white/90" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex flex-col leading-tight">
                                <span class="font-bold text-xs sm:text-sm text-[#1E1B18] font-mono">{{ $user->name ?? 'Irfan' }}</span>
                                <span class="text-[11px] font-mono text-[#524F49]">{{ $user->role ?? 'Kasir' }} Shift {{ $user->shift ?? '1' }}</span>
                            </div>
                        </div>
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

                    @if (session('status') === 'transaksi-berhasil')
                        <div class="mb-5 bg-[#DCFCE7] border border-[#86EFAC] text-[#15803D] px-4 py-2.5 rounded-xl font-mono text-xs flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm">✓</span>
                                <span>Transaksi kasir berhasil dicatat dan disimpan ke database!</span>
                            </div>
                            <button @click="$el.parentElement.remove()" class="text-[#15803D] hover:text-[#166534] font-bold">✕</button>
                        </div>
                    @endif

                    <!-- ================= TAB: PROFIL / DASHBOARD (EXACT MOCKUP) ================= -->
                    <div x-show="currentTab === 'dashboard' || currentTab === 'profil'" class="space-y-6">
                        
                        <!-- CARD 1: TOP PROFILE SUMMARY CARD -->
                        <div class="bg-white rounded-2xl border border-[#E2DFD7] p-5 sm:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
                            
                            <!-- Left: Big Avatar + User Identity -->
                            <div class="flex items-center gap-5 sm:gap-6">
                                
                                <!-- Large Circle Avatar with Camera Button -->
                                <div class="relative shrink-0">
                                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-[#C2A373] flex items-center justify-center overflow-hidden shadow-inner border border-[#B09262]/20">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            <!-- Crisp White User Silhouette Matching Mockup -->
                                            <svg class="w-16 h-16 sm:w-20 sm:h-20 text-white translate-y-2" viewBox="0 0 24 24" fill="currentColor">
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
                                    <h2 class="text-2xl sm:text-3xl font-bold font-mono text-[#1E1B18] tracking-wider">
                                        {{ $user->name ?? 'Irfan' }}
                                    </h2>
                                    <p class="text-xs sm:text-sm font-mono text-[#524F49] mt-0.5">
                                        {{ strtolower($user->role ?? 'kasir') }} Shift {{ $user->shift ?? '1' }}
                                    </p>

                                    <!-- Badges Row -->
                                    <div class="flex items-center gap-2.5 mt-3 flex-wrap">
                                        <!-- Role Pill (Tan/Camel) -->
                                        <span class="bg-[#D8B888] text-white text-[11px] font-mono font-medium px-3 py-1 rounded-full inline-flex items-center gap-1.5 shadow-xs">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                            {{ $user->role ?? 'Kasir' }}
                                        </span>

                                        <!-- Online Status Pill (Light Green) -->
                                        <span class="bg-[#DCFCE7] text-[#16A34A] text-[11px] font-mono font-semibold px-3 py-1 rounded-full inline-flex items-center gap-1.5 border border-[#86EFAC]/40">
                                            <span class="w-2 h-2 rounded-full bg-[#16A34A] animate-pulse"></span>
                                            Online
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Join Date & Last Login (Matching Mockup) -->
                            <div class="border-t md:border-t-0 md:border-l border-[#E2DFD7] pt-4 md:pt-0 md:pl-8 flex flex-col justify-center gap-3">
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
                                        <span>{{ $user->last_login_at ? $user->last_login_at->translatedFormat('d F Y, H:i') : '11 september 2026, 09:12' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BOTTOM ROW: DUAL CARDS (60% / 40%) -->
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                            <!-- CARD 2: "Informasi pengguna" (Left Column) -->
                            <div class="lg:col-span-7 xl:col-span-8 bg-white rounded-2xl border border-[#E2DFD7] p-6 shadow-sm">
                                <h3 class="text-lg font-bold font-mono text-[#1E1B18]">Informasi pengguna</h3>
                                <p class="text-xs font-mono text-[#66635C] mt-0.5 mb-6">Informasi akun anda</p>

                                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    @method('patch')

                                    <!-- Hidden Avatar File Input (Triggered by camera icon) -->
                                    <input type="file" id="avatar-input" name="avatar" class="hidden" @change="$el.form.submit()">

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
                                                value="{{ old('full_name', $user->full_name ?? 'Muhammad Irfan Ramadhan') }}"
                                                class="w-full border border-[#E0DED7] rounded-lg px-3.5 py-1.5 text-xs sm:text-sm font-mono text-[#1E1B18] bg-white focus:outline-none focus:ring-2 focus:ring-[#A88B5D]/50 focus:border-[#A88B5D] transition">
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
                                                class="w-full border border-[#E0DED7] rounded-lg px-3.5 py-1.5 text-xs sm:text-sm font-mono text-[#5C5A55] bg-[#EAE8E2] cursor-not-allowed select-none">
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
                                                class="w-full border border-[#E0DED7] rounded-lg px-3.5 py-1.5 text-xs sm:text-sm font-mono text-[#5C5A55] bg-[#EAE8E2] cursor-not-allowed select-none">
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
                                                value="{{ old('email', $user->email ?? 'muhammad.124140159@student.itera.ac.id') }}"
                                                class="w-full border border-[#E0DED7] rounded-lg px-3.5 py-1.5 text-xs sm:text-sm font-mono text-[#1E1B18] bg-white focus:outline-none focus:ring-2 focus:ring-[#A88B5D]/50 focus:border-[#A88B5D] transition">
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
                                                value="{{ old('phone', $user->phone ?? '0888 8812 4142') }}"
                                                class="w-full border border-[#E0DED7] rounded-lg px-3.5 py-1.5 text-xs sm:text-sm font-mono text-[#1E1B18] bg-white focus:outline-none focus:ring-2 focus:ring-[#A88B5D]/50 focus:border-[#A88B5D] transition">
                                            @error('phone')
                                                <p class="text-red-500 font-mono text-[11px] mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button (Floppy Disk Icon) -->
                                    <div class="pt-3">
                                        <button 
                                            type="submit" 
                                            class="bg-[#A88B5D] hover:bg-[#967B4E] text-white text-xs sm:text-sm font-mono px-4 py-2.5 rounded-lg inline-flex items-center gap-2 shadow-xs transition transform hover:scale-[1.02] cursor-pointer">
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
                            <div class="lg:col-span-5 xl:col-span-4 bg-white rounded-2xl border border-[#E2DFD7] p-6 shadow-sm flex flex-col">
                                <h3 class="text-lg font-bold font-mono text-[#1E1B18]">Informasi Tambahan</h3>
                                <p class="text-xs font-mono text-[#66635C] mt-0.5 mb-5">Informasi ini digunakan untuk keperluan operasional</p>

                                <!-- Gray Rounded Container Inside (Matching Mockup) -->
                                <div class="bg-[#EAE8E2] rounded-xl p-5 flex flex-col gap-4.5 my-auto border border-[#E0DED7]/50">
                                    
                                    <!-- Row 1: Outlet -->
                                    <div class="flex items-start justify-between gap-3 pb-3 border-b border-[#DCD9D1]/70">
                                        <div class="flex items-center gap-2.5 text-xs font-mono text-[#1E1B18] shrink-0">
                                            <!-- Store Icon -->
                                            <svg class="w-5 h-5 text-[#423F39]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                                <polyline points="9 22 9 12 15 12 15 22"/>
                                            </svg>
                                            <span>Outlet</span>
                                        </div>
                                        <span class="text-xs sm:text-sm font-mono font-bold text-[#1E1B18] text-right">
                                            {{ $user->outlet_name ?? 'Kafe Ridho' }}
                                        </span>
                                    </div>

                                    <!-- Row 2: Alamat Outlet -->
                                    <div class="flex items-start justify-between gap-3 pb-3 border-b border-[#DCD9D1]/70">
                                        <div class="flex items-center gap-2.5 text-xs font-mono text-[#1E1B18] shrink-0">
                                            <!-- Map Pin Icon -->
                                            <svg class="w-5 h-5 text-[#423F39]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                                <circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            <span>Alamat Outlet</span>
                                        </div>
                                        <span class="text-xs sm:text-sm font-mono font-medium text-[#1E1B18] text-right max-w-[180px]">
                                            {{ $user->outlet_address ?? 'Jl.way huwi no 5 lampung' }}
                                        </span>
                                    </div>

                                    <!-- Row 3: Jam Kerja -->
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-2.5 text-xs font-mono text-[#1E1B18] shrink-0">
                                            <!-- Clock Icon -->
                                            <svg class="w-5 h-5 text-[#423F39]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"/>
                                                <polyline points="12 6 12 12 16 14"/>
                                            </svg>
                                            <span>Jam Kerja</span>
                                        </div>
                                        <span class="text-xs sm:text-sm font-mono font-medium text-[#1E1B18] text-right">
                                            {{ $user->work_hours ?? '08:00 - 16:00 (Shift 1)' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= TAB 2: KASIR / POS ================= -->
                    <div x-show="currentTab === 'kasir'" class="grid grid-cols-1 lg:grid-cols-12 gap-6" style="display: none;">
                        
                        <!-- Menu Catalog (Col 8) -->
                        <div class="lg:col-span-8 space-y-5">
                            
                            <!-- Search & Filter Category -->
                            <div class="bg-white rounded-2xl border border-[#E2DFD7] p-4 flex flex-col sm:flex-row gap-3 items-center justify-between shadow-xs">
                                <div class="relative w-full sm:w-72">
                                    <input 
                                        type="text" 
                                        x-model="searchMenu" 
                                        placeholder="Cari menu kafe..." 
                                        class="w-full bg-[#FAF9F5] border border-[#E0DED7] rounded-xl pl-9 pr-3 py-1.5 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-[#A88B5D]">
                                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>

                                <div class="flex items-center gap-1.5 overflow-x-auto w-full sm:w-auto pb-1 sm:pb-0">
                                    @php $categories = ['semua', 'Kopi', 'Non-Kopi', 'Makanan', 'Dessert']; @endphp
                                    @foreach($categories as $cat)
                                        <button 
                                            @click="activeCategory = '{{ $cat }}'"
                                            :class="activeCategory === '{{ $cat }}' ? 'bg-[#A88B5D] text-white' : 'bg-[#EAE8E2] text-[#1E1B18] hover:bg-[#DCD9D1]'"
                                            class="px-3 py-1 rounded-full text-xs font-mono font-medium transition cursor-pointer shrink-0">
                                            {{ ucfirst($cat) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Menu Items Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($menus as $menu)
                                    <div 
                                        x-show="(activeCategory === 'semua' || activeCategory.toLowerCase() === '{{ strtolower($menu->kategori) }}') && ('{{ strtolower($menu->nama_menu) }}'.includes(searchMenu.toLowerCase()))"
                                        class="bg-white rounded-2xl border border-[#E2DFD7] p-4.5 shadow-xs flex flex-col justify-between hover:shadow-md transition group">
                                        <div>
                                            <div class="flex items-center justify-between gap-2 mb-2">
                                                <span class="text-[10px] font-mono uppercase tracking-wider px-2 py-0.5 rounded-md bg-[#EAE8E2] text-[#524F49]">
                                                    {{ $menu->kategori }}
                                                </span>
                                                @if($menu->status_ketersediaan === 'tersedia')
                                                    <span class="text-[10px] font-mono text-emerald-600 font-semibold flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tersedia
                                                    </span>
                                                @else
                                                    <span class="text-[10px] font-mono text-red-500 font-semibold flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Habis
                                                    </span>
                                                @endif
                                            </div>
                                            <h4 class="font-mono font-bold text-sm text-[#1E1B18] group-hover:text-[#DE541E] transition">
                                                {{ $menu->nama_menu }}
                                            </h4>
                                            <p class="font-mono text-xs text-[#A88B5D] font-bold mt-1">
                                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <button 
                                            @click="addToCart({ id: {{ $menu->id }}, nama_menu: '{{ addslashes($menu->nama_menu) }}', harga: {{ $menu->harga }} })"
                                            @if($menu->status_ketersediaan !== 'tersedia') disabled @endif
                                            class="mt-3.5 w-full py-1.5 px-3 rounded-lg text-xs font-mono font-medium flex items-center justify-center gap-1.5 transition {{ $menu->status_ketersediaan === 'tersedia' ? 'bg-[#A88B5D] hover:bg-[#967B4E] text-white cursor-pointer shadow-xs' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                                            <span>+ Tambah Pesanan</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Order Cart / Checkout (Col 4) -->
                        <div class="lg:col-span-4 bg-white rounded-2xl border border-[#E2DFD7] p-5 shadow-sm flex flex-col justify-between min-h-[500px]">
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-[#E2DFD7]">
                                    <h3 class="font-mono font-bold text-base text-[#1E1B18]">Keranjang Kasir</h3>
                                    <button 
                                        @click="cart = []" 
                                        x-show="cart.length > 0" 
                                        class="text-[11px] font-mono text-red-500 hover:underline">
                                        Reset
                                    </button>
                                </div>

                                <!-- Empty Cart State -->
                                <div x-show="cart.length === 0" class="py-16 text-center text-gray-400 font-mono text-xs flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-2 text-[#D8B888]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span>Belum ada menu yang dipilih.</span>
                                    <span class="text-[10px] mt-1 text-gray-400">Klik tombol Tambah pada menu kafe.</span>
                                </div>

                                <!-- Cart Items List -->
                                <div x-show="cart.length > 0" class="divide-y divide-[#EAE8E2] max-h-[340px] overflow-y-auto mt-2">
                                    <template x-for="item in cart" :key="item.id">
                                        <div class="py-2.5 flex items-center justify-between gap-2">
                                            <div class="flex-1">
                                                <h5 class="text-xs font-mono font-bold text-[#1E1B18]" x-text="item.nama"></h5>
                                                <span class="text-[11px] font-mono text-[#A88B5D]" x-text="formatRupiah(item.harga)"></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button @click="updateQty(item.id, -1)" class="w-5 h-5 rounded bg-[#EAE8E2] text-xs font-mono flex items-center justify-center font-bold hover:bg-[#DCD9D1]">-</button>
                                                <span class="text-xs font-mono font-bold w-4 text-center" x-text="item.qty"></span>
                                                <button @click="updateQty(item.id, 1)" class="w-5 h-5 rounded bg-[#EAE8E2] text-xs font-mono flex items-center justify-center font-bold hover:bg-[#DCD9D1]">+</button>
                                                <span class="text-xs font-mono font-bold text-[#1E1B18] w-20 text-right" x-text="formatRupiah(item.harga * item.qty)"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Cart Summary & Checkout -->
                            <div class="border-t border-[#E2DFD7] pt-4 mt-4">
                                <div class="flex items-center justify-between text-xs font-mono text-[#524F49] mb-1">
                                    <span>Total Item</span>
                                    <span x-text="cart.reduce((s, i) => s + i.qty, 0) + ' item'"></span>
                                </div>
                                <div class="flex items-center justify-between text-sm font-mono font-bold text-[#1E1B18] mb-4">
                                    <span>Total Pembayaran</span>
                                    <span class="text-[#DE541E] text-base" x-text="formatRupiah(subtotal)"></span>
                                </div>

                                <form method="POST" action="{{ route('transaksi.store') }}">
                                    @csrf
                                    <template x-for="(item, idx) in cart" :key="item.id">
                                        <div>
                                            <input type="hidden" :name="'items[' + idx + '][id]'" :value="item.id">
                                            <input type="hidden" :name="'items[' + idx + '][qty]'" :value="item.qty">
                                        </div>
                                    </template>

                                    <button 
                                        type="submit" 
                                        :disabled="cart.length === 0"
                                        class="w-full py-2.5 rounded-xl font-mono text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-sm"
                                        :class="cart.length > 0 ? 'bg-[#DE541E] hover:bg-[#C94713] text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed'">
                                        <span>Proses & Simpan Transaksi</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ================= TAB 3: RIWAYAT TRANSAKSI ================= -->
                    <div x-show="currentTab === 'riwayat'" class="bg-white rounded-2xl border border-[#E2DFD7] p-6 shadow-sm" style="display: none;">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-mono font-bold text-lg text-[#1E1B18]">Riwayat Transaksi Kasir</h3>
                                <p class="font-mono text-xs text-[#66635C]">Daftar 15 transaksi terakhir di Kafe Ridho</p>
                            </div>
                            <span class="font-mono text-xs text-[#524F49] bg-[#EAE8E2] px-3 py-1 rounded-full">
                                {{ $transaksis->count() }} Transaksi
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left font-mono text-xs">
                                <thead>
                                    <tr class="border-b border-[#E2DFD7] text-[#524F49]">
                                        <th class="py-2.5 px-3">No Transaksi</th>
                                        <th class="py-2.5 px-3">Waktu</th>
                                        <th class="py-2.5 px-3">Kasir</th>
                                        <th class="py-2.5 px-3">Detail Pesanan</th>
                                        <th class="py-2.5 px-3 text-right">Total Bayar</th>
                                        <th class="py-2.5 px-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#EAE8E2]">
                                    @forelse($transaksis as $trx)
                                        <tr class="hover:bg-[#FAF9F5] transition">
                                            <td class="py-3 px-3 font-bold text-[#1E1B18]">#TRX-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            <td class="py-3 px-3 text-[#524F49]">{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M Y, H:i') }}</td>
                                            <td class="py-3 px-3">{{ $trx->user->name ?? 'Kasir' }}</td>
                                            <td class="py-3 px-3">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($trx->details as $d)
                                                        <span class="bg-[#EAE8E2] px-2 py-0.5 rounded text-[11px]">
                                                            {{ $d->menu->nama_menu ?? 'Menu' }} ({{ $d->jumlah }}x)
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 text-right font-bold text-[#A88B5D]">
                                                Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3 px-3 text-center">
                                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded-full font-semibold text-[10px]">
                                                    Selesai
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-8 text-gray-400">
                                                Belum ada data transaksi. Buka tab Kasir untuk membuat transaksi baru.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer (Optional brand credit matching project) -->
                <footer class="mt-8 pt-4 border-t border-[#E2DFD7]/60 flex items-center justify-between text-[11px] font-mono text-[#78756F]">
                    <span>&copy; {{ date('Y') }} Tugas-PTKOM Kelompok 7 Bisnis - Kafe Ridho</span>
                    <span>Sistem Kasir & Operasional Shift 1</span>
                </footer>
            </main>
        </div>
    </div>
</x-app-layout>

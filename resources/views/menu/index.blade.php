<x-app-layout>
    <div class="p-6 bg-[#FAF7F3] min-h-screen text-gray-800">

        <!-- 1. HEADER HALAMAN & PROFIL USER (IRFAN) -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold tracking-wide">Menu</h1>
                <p class="text-gray-600 text-sm mt-1">Silahkan Pilih Menu yang Anda Ingin</p>
            </div>
            
            <!-- Profil Kasir -->
            <div class="flex items-center gap-3 bg-[#E5DFD5] px-4 py-2 rounded-full shadow-sm">
                <div class="w-8 h-8 rounded-full bg-gray-500 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=Irfan&background=6B7280&color=fff" alt="Irfan" class="w-full h-full object-cover">
                </div>
                <div class="text-xs">
                    <p class="font-bold text-gray-800 leading-tight">Irfan</p>
                    <p class="text-gray-600 text-[10px]">Kasir Shift 1</p>
                </div>
            </div>
        </div>

        <!-- 2. FILTER KATEGORI & SEARCH BAR -->
        <div class="flex justify-between items-center mb-6">
            <!-- Tab Kategori -->
            <div class="flex gap-3">
                <button class="bg-[#DFCEAF] text-gray-900 font-medium px-6 py-2 rounded-xl text-sm shadow-sm">Semua</button>
                <button class="bg-[#EFE7D8] hover:bg-[#DFCEAF] text-gray-800 font-medium px-6 py-2 rounded-xl text-sm transition">Makanan</button>
                <button class="bg-[#EFE7D8] hover:bg-[#DFCEAF] text-gray-800 font-medium px-6 py-2 rounded-xl text-sm transition">Minuman</button>
            </div>

            <!-- Search Bar -->
            <div class="flex items-center">
                <input type="text" placeholder="Cari menu..." class="bg-[#DFCEAF] px-4 py-2 rounded-l-xl text-sm focus:outline-none w-64 text-gray-800 placeholder-gray-600">
                <button class="bg-[#E05B22] text-white px-4 py-2 rounded-r-xl hover:bg-[#c84d19] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- 3. KONTEN UTAMA: KATALOG MENU (KIRI) & KERANJANG ORDER (KANAN) -->
        <div class="grid grid-cols-12 gap-6">
            
            <!-- KATALOG MENU (8 KOLOM) -->
            <div class="col-span-8 grid grid-cols-3 gap-5">
                
                {{-- Card Menu Expresso (Tersedia) --}}
                @for ($i = 0; $i < 8; $i++)
                <div class="bg-transparent rounded-xl flex flex-col justify-between">
                    <!-- Image Box -->
                    <div class="relative w-full h-36 bg-[#D8D2C5] rounded-xl overflow-hidden shadow-inner">
                        <button class="absolute top-2 right-2 bg-[#CBBBA3]/80 hover:bg-[#CBBBA3] p-1 rounded-md text-gray-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Info Menu & Tombol Tambah -->
                    <div class="flex justify-between items-end mt-3">
                        <div>
                            <h3 class="font-bold text-gray-900 text-base leading-snug">Expresso</h3>
                            <p class="text-gray-800 text-sm font-semibold mt-0.5">Rp 18.000</p>
                        </div>
                        <button class="bg-[#E05B22] hover:bg-[#c84d19] text-white p-2.5 rounded-lg transition shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @endfor

                {{-- Card Menu HABIS --}}
                <div class="bg-transparent rounded-xl flex flex-col justify-between opacity-50">
                    <div class="relative w-full h-36 bg-gray-400 rounded-xl overflow-hidden flex items-center justify-center">
                        <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase">Habis</span>
                    </div>
                    <div class="flex justify-between items-end mt-3">
                        <div>
                            <h3 class="font-bold text-gray-900 text-base leading-snug">Matcha Latte</h3>
                            <p class="text-gray-800 text-sm font-semibold mt-0.5">Rp 22.000</p>
                        </div>
                        <button disabled class="bg-gray-400 text-white p-2.5 rounded-lg cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>

            <!-- PANEL KERANJANG / ORDER (4 KOLOM) -->
            <div class="col-span-4">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between h-[520px]">
                    
                    <div>
                        <!-- Header Order -->
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Order</h2>
                        <hr class="border-gray-300 mb-4" />

                        <!-- Tempat Item Keranjang -->
                        <div class="overflow-y-auto max-h-[340px] pr-1">
                            <div class="text-center text-gray-400 py-16 text-sm">
                                Belum ada menu yang dipilih
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Total Bayar -->
                    <button class="w-full bg-[#E05B22] hover:bg-[#c84d19] text-white font-bold py-3.5 px-5 rounded-xl flex justify-between items-center shadow-md transition">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                            </svg>
                        </div>
                        <span class="tracking-wider text-base">RP 0</span>
                    </button>

                </div>
            </div>

        </div>

    </div>
</x-app-layout>
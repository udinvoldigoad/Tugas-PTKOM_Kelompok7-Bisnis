<!DOCTYPE html>
<html lang="id">
<head>
    <x-sidebar-state />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Kasir - Coffe Ridho</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
  <style>
    :root {
      --color-bg-sidebar: #D8C29D;
      --color-orange: #E06328;
      --font-brand: 'Barlow', sans-serif;
    }
    body {
      font-family: var(--font-brand);
      background-color: #ffffff;
    }
    .font-heading {
      font-family: 'Space Mono', var(--font-brand), monospace;
    }
    .app-toast {
      animation: toast-in 220ms ease-out both;
    }
    .app-toast.is-leaving {
      animation: toast-out 180ms ease-in forwards;
    }
    @keyframes toast-in {
      from { opacity: 0; transform: translateY(-10px) scale(.97); }
      to { opacity: 1; transform: none; }
    }
    @keyframes toast-out {
      to { opacity: 0; transform: translateY(-8px) scale(.98); }
    }
  </style>
</head>

<body class="h-screen w-screen overflow-hidden bg-white text-[#1A1208]">

  <div id="toast-region" class="pointer-events-none fixed inset-x-4 top-4 z-[100] flex flex-col items-end gap-2 sm:left-auto sm:w-[22rem]" role="status" aria-live="polite" aria-atomic="true"></div>

  <!-- Container Utama Full Screen Edge-to-Edge -->
  <div class="profile-shell w-full h-full relative">

    <!-- ================= SIDEBAR ================= -->
    <x-profile-sidebar active="kasir" />

    <!-- ================= MAIN CONTENT ================= -->
    <main class="min-w-0 flex-1 p-5 md:p-6 flex flex-col overflow-hidden h-full bg-white">
      
      <!-- Header Atas -->
      <div class="flex items-center justify-between gap-4 mb-5 shrink-0">
        <div>
          <h1 class="text-2xl md:text-3xl font-extrabold font-heading text-[#1A1208] tracking-tight">Menu</h1>
          <p class="text-xs md:text-sm font-semibold text-[#1A1208]/80 mt-0.5">Silahkan Pilih Menu yang Anda Inginkan</p>
        </div>

        <div class="flex items-center gap-3 bg-[#E6DDD0] px-4 py-1.5 rounded-full shrink-0">
          <div class="w-8 h-8 rounded-full bg-[#8C7A6B] flex items-center justify-center text-white text-xs font-bold">N</div>
          <div class="text-xs leading-tight">
            <p class="font-bold text-[#1A1208]">Niken</p>
            <p class="text-[#1A1208]/70 font-medium">Kasir Shift 1</p>
          </div>
        </div>
      </div>

      <!-- Area Layout Utama -->
      <div class="flex flex-col lg:flex-row gap-5 flex-1 min-h-0 overflow-hidden">
        
        <!-- KOLOM KIRI: PRODUK & FILTER -->
        <div class="flex-1 flex flex-col min-h-0">
          
          <!-- Header Bar Tambah Menu & Filter -->
          <div class="flex items-center justify-between mb-4 shrink-0 gap-2">
            <!-- Tombol Tambah Menu -->
            <button onclick="openAddModal()" class="px-4 py-2 rounded-xl bg-[#E06328] hover:bg-[#c9521c] text-white font-bold text-xs shadow-sm flex items-center gap-1.5 transition">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
              </svg>
              Tambah Menu
            </button>

            <!-- Tombol Filter Kategori (Semua, Makanan, Minuman) -->
            <div class="flex items-center gap-2">
              <button id="btn-filter-semua" onclick="setFilterCategory('Semua')" class="px-5 py-2 rounded-xl bg-[#D8C29D] text-[#1A1208] font-bold text-xs shadow-sm transition">Semua</button>
              <button id="btn-filter-makanan" onclick="setFilterCategory('Makanan')" class="px-5 py-2 rounded-xl bg-[#E8D8C3] hover:bg-[#D8C29D]/70 text-[#1A1208] font-bold text-xs transition">Makanan</button>
              <button id="btn-filter-minuman" onclick="setFilterCategory('Minuman')" class="px-5 py-2 rounded-xl bg-[#E8D8C3] hover:bg-[#D8C29D]/70 text-[#1A1208] font-bold text-xs transition">Minuman</button>
            </div>
          </div>

          <!-- Grid Card Produk -->
          <div id="product-grid" class="grid auto-rows-max grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 overflow-y-auto pr-1 pb-2">
          </div>
        </div>

        <!-- KOLOM KANAN: PENCARIAN & PANEL ORDER -->
        <div class="w-full lg:w-80 flex flex-col gap-4 shrink-0">
          
          <!-- Input Cari -->
          <div class="relative w-full shrink-0">
            <input type="text" id="search-input" oninput="renderProducts()" placeholder="Cari..." class="w-full bg-[#D8C29D] text-[#1A1208] placeholder-[#1A1208]/60 pl-4 pr-10 py-2.5 rounded-xl text-xs font-medium focus:outline-none">
            <button onclick="renderProducts()" class="absolute right-0 top-0 bottom-0 w-10 bg-[#E06328] hover:bg-[#c9521c] rounded-r-xl flex items-center justify-center text-white transition">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </button>
          </div>

          <!-- Panel Order -->
          <div class="flex-1 bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex flex-col justify-between overflow-hidden">
            
            <!-- Header Panel Order -->
            <div class="shrink-0">
              <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                  <h2 class="text-xl font-bold font-heading text-[#1A1208]">Order</h2>
                  <span class="bg-[#A08865] text-white text-[11px] font-mono px-2 py-0.5 rounded-full font-bold">#TRX-001</span>
                </div>
                <!-- Tombol Trash -->
                <button onclick="clearCart()" class="w-8 h-8 bg-[#8B2626] hover:bg-red-800 text-white rounded-full flex items-center justify-center transition shadow-sm" title="Hapus Semua Order">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                  </svg>
                </button>
              </div>
              <hr class="border-gray-200">
            </div>

            <!-- List Item Orderan -->
            <div id="cart-list" class="flex-1 overflow-y-auto pr-1 flex flex-col my-2 divide-y divide-gray-100">
            </div>

            <!-- TAMPILAN AWAL: Footer Total & Tombol Keranjang -->
            <div id="cart-footer-btn" class="pt-3 border-t border-gray-200 shrink-0">
              <button onclick="showPaymentSection()" class="w-full bg-[#E06328] hover:bg-[#c9521c] text-white font-bold py-3 px-4 rounded-xl flex items-center justify-between transition shadow-md">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-9.83-3.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1.003 1.003 0 0020 4H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.13 0-.25-.11-.25-.25z"/>
                </svg>
                <span id="cart-total" class="text-base tracking-wider font-heading font-extrabold">RP 0</span>
              </button>
            </div>

            <!-- TAMPILAN TAMBAHAN: Rincian Tagihan & Metode Pembayaran -->
            <div id="payment-section" class="shrink-0 pt-2 flex-col gap-2 hidden">
              
              <!-- Kotak Rincian Tagihan -->
              <div class="bg-[#E4CEB1] rounded-2xl p-3 flex flex-col gap-1 text-[#1A1208] text-xs font-heading">
                <div class="flex justify-between items-center font-semibold">
                  <span id="subtotal-label">Sub Total (0 Item)</span>
                  <span id="subtotal-val">Rp 0</span>
                </div>
                <div class="flex justify-between items-center font-semibold">
                  <span>PPN (10%)</span>
                  <span id="ppn-val">Rp 0</span>
                </div>
                <div class="border-t-2 border-dashed border-[#1A1208]/20 my-1"></div>

                <div class="flex justify-between items-center text-sm font-extrabold tracking-tight">
                  <span>Total Tagihan</span>
                  <span id="total-tagihan-val">Rp. 0</span>
                </div>
              </div>

              <!-- Kotak Metode Pembayaran & Cetak Struk -->
              <div class="bg-[#A08865] rounded-2xl p-3 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-1.5">
                    <button onclick="hidePaymentSection()" title="Kembali" class="text-white hover:text-gray-200">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <span class="text-white text-xs font-extrabold font-heading leading-tight">Metode<br>Pembayaran</span>
                  </div>
                  
                  <div class="flex items-center gap-2">
                    <button type="button" onclick="selectPayment('cash')" id="pay-cash" class="bg-white text-[#1A1208] rounded-xl px-3 py-1.5 flex flex-col items-center justify-center shadow-sm transition">
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 6h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm7 3a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0 1.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z"/>
                      </svg>
                      <span class="text-[10px] font-bold font-heading mt-0.5">Cash</span>
                    </button>

                    <button type="button" onclick="selectPayment('qris')" id="pay-qris" class="bg-[#D3C4B1] text-[#1A1208] rounded-xl px-3 py-1.5 flex flex-col items-center justify-center opacity-80 transition">
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v3h-3v-3zm-5 0h3v3h-3v-3zm0 5h3v3h-3v-3zm5 0h3v3h-3v-3z"/>
                      </svg>
                      <span class="text-[10px] font-bold font-heading mt-0.5">Qris</span>
                    </button>
                  </div>
                </div>

                <button id="process-transaction-button" onclick="processPayment()" class="w-full bg-[#E06328] hover:bg-[#c9521c] disabled:cursor-wait disabled:opacity-60 text-white font-extrabold font-heading py-2.5 rounded-xl text-sm transition shadow-md tracking-wider">
                  Cetak Struk
                </button>
              </div>

            </div>

          </div>

        </div>

      </div>
    </main>

  </div>

  <!-- ================= MODAL TAMBAH MENU BARU ================= -->
  <div id="addMenuModal" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-50 flex items-center justify-center hidden p-3 md:p-4">
    <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl p-4 md:p-5 relative overflow-y-auto max-h-[92vh]">
      <button type="button" onclick="closeAddModal()" class="absolute top-3.5 right-3.5 text-[#1A1208]/50 hover:text-[#1A1208] hover:bg-gray-100 rounded-lg p-1.5 transition flex items-center justify-center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <h2 class="text-lg md:text-xl font-extrabold font-heading text-[#1A1208] mb-4 pr-8">Tambah Menu Baru</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3.5">
        <div>
          <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Nama Produk</label>
          <input type="text" id="add-name" placeholder="misal: Matcha Latte" class="w-full bg-[#D9D9D9] text-[#1A1208] font-bold px-3 py-2 rounded-lg text-xs focus:outline-none">
        </div>
        <div>
          <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Harga (Rp)</label>
          <input type="number" id="add-price" placeholder="22000" class="w-full bg-[#D9D9D9] text-[#1A1208] font-bold px-3 py-2 rounded-lg text-xs focus:outline-none">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3.5">
        <div>
          <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Kategori Menu</label>
          <div class="relative">
            <select id="add-category" class="w-full bg-[#D9D9D9] text-[#1A1208] font-bold px-3 py-2 rounded-lg text-xs focus:outline-none appearance-none cursor-pointer pr-7">
              <option value="Kopi">Kopi</option>
              <option value="Non-Kopi">Non-Kopi</option>
              <option value="Makanan">Makanan</option>
              <option value="Dessert">Dessert</option>
            </select>
            <div class="pointer-events-none absolute right-2.5 top-0 bottom-0 flex items-center text-[#1A1208]">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </div>
          </div>
        </div>
        <div>
          <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Status Ketersediaan</label>
          <select id="add-status" class="w-full bg-[#D9D9D9] text-[#1A1208] font-bold px-3 py-2 rounded-lg text-xs focus:outline-none cursor-pointer">
            <option value="tersedia">Tersedia</option>
            <option value="habis">Habis</option>
          </select>
        </div>
      </div>

      <div class="mb-5">
        <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Foto Menu</label>
        <input type="file" id="add-photo" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-lg bg-[#D9D9D9] px-3 py-2 text-xs file:mr-3 file:rounded-md file:border-0 file:bg-[#E2D4BB] file:px-3 file:py-1 file:font-bold">
        <p class="mt-1 text-[10px] text-[#1A1208]/70">Format JPG, PNG, atau WebP. Maksimal 2 MB.</p>
      </div>

      <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-gray-100">
        <button type="button" onclick="closeAddModal()" class="bg-gray-200 hover:bg-gray-300 text-[#1A1208] font-bold px-5 py-2 rounded-xl text-xs font-heading transition">Batal</button>
        <button type="button" onclick="saveNewMenu()" class="bg-[#E06328] hover:bg-[#c9521c] text-white font-bold px-6 py-2 rounded-xl text-xs font-heading transition shadow-sm">Tambah Menu</button>
      </div>

    </div>
  </div>

  <!-- ================= MODAL EDIT DETAIL MENU ================= -->
  <div id="editMenuModal" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-50 flex items-center justify-center hidden p-3 md:p-4">
    <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl p-4 md:p-5 relative overflow-y-auto max-h-[92vh]">
      <button type="button" onclick="closeEditModal()" class="absolute top-3.5 right-3.5 text-[#1A1208]/50 hover:text-[#1A1208] hover:bg-gray-100 rounded-lg p-1.5 transition flex items-center justify-center" title="Tutup">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <h2 class="text-lg md:text-xl font-extrabold font-heading text-[#1A1208] mb-3 pr-8">Edit Detail Menu</h2>

      <div class="bg-[#EBEBEB] rounded-xl p-3 flex items-center gap-3.5 mb-3.5">
        <div id="modal-photo-preview" class="w-12 h-12 rounded-full bg-[#755953] shrink-0 flex items-center justify-center text-white font-bold text-xs overflow-hidden">
          Susu
        </div>
        <div class="flex flex-col gap-0.5">
          <h4 class="font-bold font-heading text-[#1A1208] text-xs md:text-sm">Foto Profil Menu</h4>
          <p class="text-[10px] text-[#1A1208]/70 font-mono mb-1">Format JPG, PNG atau WebP (Max 2MB)</p>
          <div class="flex items-center gap-2">
            <label class="cursor-pointer bg-[#E2D4BB] hover:bg-[#d0bf9f] text-[#1A1208] px-2.5 py-1 rounded-md text-[11px] font-bold font-heading flex items-center gap-1 transition">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
              </svg>
              Upload foto
              <input type="file" id="edit-photo" class="hidden" accept="image/jpeg,image/png,image/webp" onchange="previewEditPhoto(this)">
            </label>
            <button type="button" onclick="removeEditPhoto()" class="bg-white hover:bg-gray-100 text-[#8B2626] border border-gray-200 px-2.5 py-1 rounded-md text-[11px] font-bold font-heading flex items-center gap-1 transition">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
              </svg>
              Hapus foto
            </button>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 mb-3.5">
        <div>
          <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Nama produk</label>
          <input type="text" id="edit-name" class="w-full bg-[#D9D9D9] text-[#1A1208] font-bold px-3 py-1.5 rounded-lg text-xs focus:outline-none">
        </div>
        <div>
          <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Kategori Menu</label>
          <div class="relative">
            <select id="edit-category" class="w-full bg-[#D9D9D9] text-[#1A1208] font-bold px-3 py-1.5 rounded-lg text-xs focus:outline-none appearance-none cursor-pointer pr-7">
              <option value="Kopi">Kopi</option>
              <option value="Non-Kopi">Non-Kopi</option>
              <option value="Makanan">Makanan</option>
              <option value="Dessert">Dessert</option>
            </select>
            <div class="pointer-events-none absolute right-2.5 top-0 bottom-0 flex items-center text-[#1A1208]">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </div>
          </div>
        </div>
        <div>
          <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Harga (Rp)</label>
          <input type="number" id="edit-price" min="1" class="w-full bg-[#D9D9D9] text-[#1A1208] font-bold px-3 py-1.5 rounded-lg text-xs focus:outline-none">
        </div>
      </div>

      <div class="mb-3.5">
        <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Status Ketersediaan Stok</label>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
          <button type="button" onclick="setStatus('instock')" id="status-instock" class="bg-[#D9D9D9] text-[#1A1208] py-1.5 px-2 rounded-lg text-[11px] font-bold font-heading transition truncate">Tersedia (In Stock)</button>
          <button type="button" onclick="setStatus('out')" id="status-out" class="bg-[#D9D9D9] text-[#1A1208] py-1.5 px-2 rounded-lg text-[11px] font-bold font-heading transition truncate">Habis Sementara</button>
          <button type="button" onclick="setStatus('archive')" id="status-archive" class="bg-[#D9D9D9] text-[#1A1208] py-1.5 px-2 rounded-lg text-[11px] font-bold font-heading transition truncate">Arsipkan Menu</button>
        </div>
      </div>

      <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-gray-100">
        <button type="button" onclick="deleteMenu()" class="bg-[#8B2626] hover:bg-red-800 text-white font-bold px-6 py-2 rounded-xl text-xs font-heading transition shadow-sm">Hapus</button>
        <button type="button" onclick="saveMenu()" class="bg-[#A88C52] hover:bg-[#937842] text-white font-bold px-6 py-2 rounded-xl text-xs font-heading transition shadow-sm">Simpan</button>
      </div>

    </div>
  </div>

  <!-- ================= MODAL NOTIFIKASI ================= -->
  <div id="deleteSuccessModal" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-50 flex items-center justify-center hidden p-3">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-5 relative">
      <button type="button" onclick="closeModal('deleteSuccessModal')" class="absolute top-3.5 right-3.5 w-7 h-7 bg-gray-200 hover:bg-gray-300 text-[#1A1208] rounded-full flex items-center justify-center transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <div class="flex items-center gap-3.5 mb-5 pr-6">
        <div class="w-12 h-12 rounded-full bg-[#A88C52] shrink-0"></div>
        <div>
          <h3 class="font-extrabold font-heading text-[#1A1208] text-base md:text-lg">Menu Berhasil Dihapus</h3>
          <p class="text-xs text-[#1A1208]/90 mt-0.5">
            Item <span id="deleted-item-name" class="font-bold text-[#A88C52]">Menu</span> berhasil dihapus
          </p>
        </div>
      </div>

      <div class="flex items-center gap-2.5">
        <button type="button" onclick="undoDelete()" class="flex-1 bg-[#D9D9D9] hover:bg-gray-300 text-[#1A1208] font-bold py-2.5 px-3 rounded-full text-xs font-heading flex items-center justify-center gap-2 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
          </svg>
          Batalkan Penghapusan
        </button>
        <button type="button" onclick="closeModal('deleteSuccessModal')" class="flex-1 bg-[#A88C52] hover:bg-[#937842] text-white font-bold py-2.5 px-3 rounded-full text-xs font-heading flex items-center justify-center gap-1.5 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
          Kembali ke Menu katalog
        </button>
      </div>
    </div>
  </div>

  <!-- SCRIPT LOGIKA -->
  <script>
    const csrfToken = @json(csrf_token());
    const menuBaseUrl = @json(url('/kelola-menu'));
    const cartBaseUrl = @json(url('/kasir/keranjang'));
    const transactionStoreUrl = @json(route('transactions.store'));
    const storageBaseUrl = @json(asset('storage'));
    let products = @json($menus).map(menu => mapMenu(menu));
    let cart = Object.values(@json($cart)).map(item => ({
      id: Number(item.id_menu),
      name: item.nama_menu,
      price: Number(item.harga),
      qty: Number(item.jumlah),
      note: '',
    }));
    let noteEditorItemId = null;
    let currentEditId = null;
    let selectedStatus = 'instock';
    let lastDeletedItem = null;
    let removeCurrentPhoto = false;
    let selectedPaymentMethod = 'cash';
    let checkoutIdempotencyKey = null;
    
    // VARIABEL FILTER KATEGORI TERPILIH
    let currentCategoryFilter = 'Semua';

    function formatRupiah(num) {
      return 'Rp ' + num.toLocaleString('id-ID');
    }

    function escapeHtml(value) {
      return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
    }

    function showToast(message, type = 'error') {
      const toastRegion = document.getElementById('toast-region');
      const toast = document.createElement('div');
      const isSuccess = type === 'success';
      toast.className = `app-toast pointer-events-auto flex w-full items-start gap-3 rounded-xl border px-4 py-3 text-sm font-bold shadow-lg ${isSuccess ? 'border-green-200 bg-green-50 text-green-800' : 'border-red-200 bg-red-50 text-red-800'}`;
      toast.innerHTML = `
        <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full ${isSuccess ? 'bg-green-600' : 'bg-red-600'} text-xs text-white">${isSuccess ? '✓' : '!'}</span>
        <span class="min-w-0 flex-1">${escapeHtml(message)}</span>
        <button type="button" class="text-lg leading-none opacity-60 hover:opacity-100" aria-label="Tutup pesan">×</button>
      `;
      const removeToast = () => {
        toast.classList.add('is-leaving');
        window.setTimeout(() => toast.remove(), 180);
      };
      toast.querySelector('button').addEventListener('click', removeToast);
      toastRegion.appendChild(toast);
      window.setTimeout(removeToast, isSuccess ? 3500 : 5000);
    }

    function mapMenu(menu) {
      return {
        id: menu.id,
        name: menu.nama_menu,
        price: Number(menu.harga),
        category: menu.kategori,
        photo: menu.foto,
        photoUrl: menu.foto ? `${storageBaseUrl}/${menu.foto}` : null,
        status: menu.status_ketersediaan === 'tersedia' ? 'instock' : 'out',
      };
    }

    async function sendMenuRequest(url, options) {
      const response = await fetch(url, {
        ...options,
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          ...(options.headers || {}),
        },
      });
      const payload = await response.json();

      if (!response.ok) {
        const messages = payload.errors ? Object.values(payload.errors).flat() : [payload.message || 'Terjadi kesalahan.'];
        throw new Error(messages.join('\n'));
      }

      return payload;
    }

    async function sendCartRequest(url, options) {
      const response = await fetch(url, {
        ...options,
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          ...(options.headers || {}),
        },
      });
      const payload = await response.json();

      if (!response.ok) {
        const messages = payload.errors ? Object.values(payload.errors).flat() : [payload.message || 'Keranjang gagal diperbarui.'];
        throw new Error(messages.join('\n'));
      }

      return payload;
    }

    // --- FUNGSI GANTI FILTER KATEGORI ---
    function setFilterCategory(category) {
      currentCategoryFilter = category;

      const btnSemua = document.getElementById('btn-filter-semua');
      const btnMakanan = document.getElementById('btn-filter-makanan');
      const btnMinuman = document.getElementById('btn-filter-minuman');

      const activeClass = "px-5 py-2 rounded-xl bg-[#D8C29D] text-[#1A1208] font-bold text-xs shadow-sm transition";
      const inactiveClass = "px-5 py-2 rounded-xl bg-[#E8D8C3] hover:bg-[#D8C29D]/70 text-[#1A1208] font-bold text-xs transition";

      btnSemua.className = (category === 'Semua') ? activeClass : inactiveClass;
      btnMakanan.className = (category === 'Makanan') ? activeClass : inactiveClass;
      btnMinuman.className = (category === 'Minuman') ? activeClass : inactiveClass;

      renderProducts();
    }

    // --- FUNGSI RENDER PRODUK DENGAN DUA FILTER (TEXT CARI + KATEGORI) ---
    function renderProducts() {
      const grid = document.getElementById('product-grid');
      grid.innerHTML = '';

      const query = document.getElementById('search-input').value.trim().toLowerCase();

      const filtered = products.filter(item => {
        // Filter Kata Kunci (Pencarian)
        const matchesSearch = item.name.toLowerCase().includes(query);

        // Filter Kategori
        let matchesCategory = false;
        if (currentCategoryFilter === 'Semua') {
          matchesCategory = true;
        } else if (currentCategoryFilter === 'Makanan') {
          matchesCategory = (item.category === 'Makanan');
        } else if (currentCategoryFilter === 'Minuman') {
          matchesCategory = (item.category === 'Kopi' || item.category === 'Non-Kopi' || item.category === 'Minuman');
        }

        return matchesSearch && matchesCategory;
      });

      const sortedProducts = [...filtered].sort((a, b) => {
        if (a.status === 'archive' && b.status !== 'archive') return 1;
        if (a.status !== 'archive' && b.status === 'archive') return -1;
        return 0;
      });

      if (sortedProducts.length === 0) {
        grid.innerHTML = `
          <div class="col-span-full flex flex-col items-center justify-center py-10 text-gray-400">
            <p class="text-xs font-bold font-heading">Menu tidak ditemukan</p>
          </div>
        `;
        return;
      }

      sortedProducts.forEach(item => {
        const isArchive = item.status === 'archive';
        const isOutOfStock = item.status === 'out' && !isArchive;

        let badgeHtml = '';
        if (isArchive) {
          badgeHtml = '<span class="absolute bg-gray-700 text-white text-[10px] font-bold px-2 py-0.5 rounded-full font-heading">Diarsipkan</span>';
        } else if (isOutOfStock) {
          badgeHtml = '<span class="absolute bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full font-heading">Habis</span>';
        }

        const isDisabled = isArchive || isOutOfStock;

        grid.innerHTML += `
          <div class="grid min-w-0 grid-rows-[auto_3.5rem] ${isArchive ? 'opacity-40 grayscale' : isOutOfStock ? 'opacity-60' : ''}">
            <div class="relative flex aspect-square w-full items-center justify-center overflow-hidden rounded-xl bg-[#D0D0D0]">
              <button onclick="openEditModal(${item.id})" class="absolute top-2 right-2 w-6 h-6 bg-[#C2C2C2] hover:bg-gray-400 rounded-full flex items-center justify-center text-[#1A1208] text-xs font-bold transition z-10" title="Edit Detail Menu">⋮</button>
              ${badgeHtml}
              ${item.photoUrl ? `<img src="${escapeHtml(item.photoUrl)}" alt="${escapeHtml(item.name)}" class="h-full w-full rounded-xl object-cover object-top">` : `<span class="text-sm font-bold text-[#1A1208]/50">${escapeHtml(item.name.substring(0, 2).toUpperCase())}</span>`}
            </div>
            <div class="flex min-w-0 items-center justify-between gap-2 pt-2">
              <div class="min-w-0">
                <h4 class="truncate font-bold text-sm text-[#1A1208] font-heading leading-tight" title="${escapeHtml(item.name)}">${escapeHtml(item.name)}</h4>
                <p class="text-xs font-bold text-[#1A1208]/90">${formatRupiah(item.price)}</p>
              </div>
              <button onclick="addToCart(${item.id})" ${isDisabled ? 'disabled class="w-7 h-7 shrink-0 bg-gray-400 text-white rounded-lg flex items-center justify-center font-bold text-base cursor-not-allowed"' : 'class="w-7 h-7 shrink-0 bg-[#E06328] hover:bg-[#c9521c] text-white rounded-lg flex items-center justify-center font-bold text-base transition shadow-sm"'}>+</button>
            </div>
          </div>
        `;
      });
    }

    // --- FUNGSI SHOW / HIDE PANEL PEMBAYARAN ---
    function showPaymentSection() {
      if (cart.length === 0) {
        showToast('Keranjang masih kosong! Silakan pilih menu terlebih dahulu.');
        return;
      }
      document.getElementById('cart-footer-btn').classList.add('hidden');
      const paySec = document.getElementById('payment-section');
      paySec.classList.remove('hidden');
      paySec.classList.add('flex');
    }

    function hidePaymentSection() {
      const paySec = document.getElementById('payment-section');
      paySec.classList.add('hidden');
      paySec.classList.remove('flex');
      document.getElementById('cart-footer-btn').classList.remove('hidden');
    }

    function selectPayment(method) {
      selectedPaymentMethod = method;
      const btnCash = document.getElementById('pay-cash');
      const btnQris = document.getElementById('pay-qris');

      if (method === 'cash') {
        btnCash.className = "bg-white text-[#1A1208] rounded-xl px-3 py-1.5 flex flex-col items-center justify-center shadow-sm transition";
        btnQris.className = "bg-[#D3C4B1] text-[#1A1208] rounded-xl px-3 py-1.5 flex flex-col items-center justify-center opacity-80 transition";
      } else {
        btnQris.className = "bg-white text-[#1A1208] rounded-xl px-3 py-1.5 flex flex-col items-center justify-center shadow-sm transition";
        btnCash.className = "bg-[#D3C4B1] text-[#1A1208] rounded-xl px-3 py-1.5 flex flex-col items-center justify-center opacity-80 transition";
      }
    }

    function resetProcessButton() {
      const processButton = document.getElementById('process-transaction-button');
      if (!processButton) return;

      processButton.disabled = false;
      processButton.textContent = 'Cetak Struk';
    }

    function createIdempotencyKey() {
      if (window.crypto?.randomUUID) return window.crypto.randomUUID();

      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, character => {
        const random = window.crypto.getRandomValues(new Uint8Array(1))[0] & 15;
        const value = character === 'x' ? random : (random & 3) | 8;

        return value.toString(16);
      });
    }

    async function processPayment() {
      if (cart.length === 0) return;
      const processButton = document.getElementById('process-transaction-button');
      if (processButton.disabled) return;

      processButton.disabled = true;
      processButton.textContent = 'Menyimpan...';
      checkoutIdempotencyKey ??= createIdempotencyKey();

      try {
        const payload = await sendCartRequest(transactionStoreUrl, {
          method: 'POST',
          body: JSON.stringify({
            metode_pembayaran: selectedPaymentMethod,
            idempotency_key: checkoutIdempotencyKey,
          }),
        });
        checkoutIdempotencyKey = null;
        cart = [];
        noteEditorItemId = null;
        hidePaymentSection();
        renderCart();
        showToast(`Transaksi #${payload.transaksi.id} berhasil disimpan. Struk siap dicetak.`, 'success');
      } catch (error) {
        showToast(error.message);
      } finally {
        resetProcessButton();
      }
    }

    // --- MODAL & CART FUNCTIONS ---
    function openAddModal() {
      document.getElementById('add-name').value = '';
      document.getElementById('add-price').value = '';
      document.getElementById('add-category').value = 'Kopi';
      document.getElementById('add-status').value = 'tersedia';
      document.getElementById('add-photo').value = '';
      document.getElementById('addMenuModal').classList.remove('hidden');
    }

    function closeAddModal() {
      document.getElementById('addMenuModal').classList.add('hidden');
    }

    async function saveNewMenu() {
      const name = document.getElementById('add-name').value.trim();
      const price = parseInt(document.getElementById('add-price').value) || 0;
      const category = document.getElementById('add-category').value;

      if (!name || price <= 0) {
        showToast('Mohon isi nama produk dan harga dengan benar.');
        return;
      }

      const formData = new FormData();
      formData.append('nama_menu', name);
      formData.append('harga', price);
      formData.append('kategori', category);
      formData.append('status_ketersediaan', document.getElementById('add-status').value);

      const photo = document.getElementById('add-photo').files[0];
      if (photo) formData.append('foto', photo);

      try {
        const payload = await sendMenuRequest(menuBaseUrl, { method: 'POST', body: formData });
        products.push(mapMenu(payload.menu));
        renderProducts();
        closeAddModal();
      } catch (error) {
        showToast(error.message);
      }
    }

    function closeModal(modalId) {
      document.getElementById(modalId).classList.add('hidden');
    }

    function openEditModal(productId) {
      const p = products.find(prod => prod.id === productId);
      if (!p) return;

      currentEditId = productId;
      removeCurrentPhoto = false;
      document.getElementById('edit-name').value = p.name;
      document.getElementById('edit-category').value = p.category || 'Kopi';
      document.getElementById('edit-price').value = p.price;
      document.getElementById('edit-photo').value = '';
      updatePhotoPreview(p.photoUrl, p.name);

      setStatus(p.status || 'instock');
      document.getElementById('editMenuModal').classList.remove('hidden');
    }

    function closeEditModal() {
      document.getElementById('editMenuModal').classList.add('hidden');
    }

    function updatePhotoPreview(photoUrl, name) {
      const preview = document.getElementById('modal-photo-preview');
      preview.innerHTML = photoUrl
        ? `<img src="${escapeHtml(photoUrl)}" alt="${escapeHtml(name)}" class="h-full w-full bg-[#F4F0EA] object-contain p-2">`
        : escapeHtml(name.substring(0, 4));
    }

    function previewEditPhoto(input) {
      const file = input.files[0];
      if (!file) return;

      removeCurrentPhoto = false;
      updatePhotoPreview(URL.createObjectURL(file), document.getElementById('edit-name').value);
    }

    function removeEditPhoto() {
      removeCurrentPhoto = true;
      document.getElementById('edit-photo').value = '';
      updatePhotoPreview(null, document.getElementById('edit-name').value || 'Menu');
    }

    function setStatus(statusType) {
      selectedStatus = statusType;
      const btnIn = document.getElementById('status-instock');
      const btnOut = document.getElementById('status-out');
      const btnArc = document.getElementById('status-archive');

      [btnIn, btnOut, btnArc].forEach(btn => {
        btn.className = "bg-[#D9D9D9] text-[#1A1208] py-1.5 px-2 rounded-lg text-[11px] font-bold font-heading transition truncate";
      });

      if (statusType === 'instock') {
        btnIn.className = "bg-[#A88C52] text-white py-1.5 px-2 rounded-lg text-[11px] font-bold font-heading transition truncate";
      } else if (statusType === 'out') {
        btnOut.className = "bg-[#A88C52] text-white py-1.5 px-2 rounded-lg text-[11px] font-bold font-heading transition truncate";
      } else if (statusType === 'archive') {
        btnArc.className = "bg-[#A88C52] text-white py-1.5 px-2 rounded-lg text-[11px] font-bold font-heading transition truncate";
      }
    }

    async function saveMenu() {
      if (!currentEditId) return;

      if (selectedStatus === 'archive') {
        await deleteMenu();
        return;
      }

      const formData = new FormData();
      formData.append('_method', 'PATCH');
      formData.append('nama_menu', document.getElementById('edit-name').value.trim());
      formData.append('kategori', document.getElementById('edit-category').value);
      formData.append('harga', document.getElementById('edit-price').value);
      formData.append('status_ketersediaan', selectedStatus === 'instock' ? 'tersedia' : 'habis');
      if (removeCurrentPhoto) formData.append('hapus_foto', '1');

      const photo = document.getElementById('edit-photo').files[0];
      if (photo) formData.append('foto', photo);

      try {
        const payload = await sendMenuRequest(`${menuBaseUrl}/${currentEditId}`, { method: 'POST', body: formData });
        const index = products.findIndex(product => product.id === currentEditId);
        products[index] = mapMenu(payload.menu);
        renderProducts();
        closeEditModal();
      } catch (error) {
        showToast(error.message);
      }
    }

    async function deleteMenu() {
      if (!currentEditId) return;

      const p = products.find(item => item.id === currentEditId);
      if (!p) return;

      try {
        await sendMenuRequest(`${menuBaseUrl}/${currentEditId}`, { method: 'DELETE' });
        lastDeletedItem = { ...p };
        products = products.filter(item => item.id !== currentEditId);
        renderProducts();
        closeEditModal();
        document.getElementById('deleted-item-name').innerText = p.name;
        document.getElementById('deleteSuccessModal').classList.remove('hidden');
      } catch (error) {
        showToast(error.message);
      }
    }

    async function undoDelete() {
      if (!lastDeletedItem) return;

      try {
        const payload = await sendMenuRequest(`${menuBaseUrl}/${lastDeletedItem.id}/restore`, { method: 'POST' });
        products.push(mapMenu(payload.menu));
        lastDeletedItem = null;
        renderProducts();
        closeModal('deleteSuccessModal');
      } catch (error) {
        showToast(error.message);
      }
    }

    async function addToCart(productId) {
      const product = products.find(p => p.id === productId);
      if (!product || product.status !== 'instock') return;

      try {
        const payload = await sendCartRequest(`${cartBaseUrl}/tambah/${productId}`, {
          method: 'POST',
          body: JSON.stringify({ jumlah: 1 }),
        });
        const existing = cart.find(item => item.id === productId);

        if (existing) {
          existing.qty = Number(payload.item.jumlah);
          existing.price = Number(payload.item.harga);
        } else {
          cart.push({
            id: Number(payload.item.id_menu),
            name: payload.item.nama_menu,
            price: Number(payload.item.harga),
            qty: Number(payload.item.jumlah),
            note: '',
          });
        }

        renderCart();
      } catch (error) {
        showToast(error.message);
      }
    }

    async function changeQty(productId, delta) {
      const item = cart.find(i => i.id === productId);
      if (!item) return;

      const nextQuantity = item.qty + delta;

      try {
        if (nextQuantity <= 0) {
          await sendCartRequest(`${cartBaseUrl}/hapus/${productId}`, { method: 'DELETE' });
          cart = cart.filter(cartItem => cartItem.id !== productId);

          if (noteEditorItemId === productId) {
            noteEditorItemId = null;
          }
        } else {
          const payload = await sendCartRequest(`${cartBaseUrl}/update/${productId}`, {
            method: 'POST',
            body: JSON.stringify({ jumlah: nextQuantity }),
          });
          item.qty = Number(payload.item.jumlah);
          item.price = Number(payload.item.harga);
        }

        renderCart();
      } catch (error) {
        showToast(error.message);
        window.setTimeout(() => window.location.reload(), 1600);
      }
    }

    async function clearCart() {
      if (cart.length === 0) return;

      try {
        await sendCartRequest(cartBaseUrl, { method: 'DELETE' });
        cart = [];
        noteEditorItemId = null;
        hidePaymentSection();
        renderCart();
      } catch (error) {
        showToast(error.message);
      }
    }

    function openItemNote(productId) {
      noteEditorItemId = productId;
      renderCart();

      requestAnimationFrame(() => {
        const input = document.getElementById(`cart-note-${productId}`);
        input?.focus();
        input?.setSelectionRange(input.value.length, input.value.length);
      });
    }

    function closeItemNote() {
      noteEditorItemId = null;
      renderCart();
    }

    function saveItemNote(productId) {
      const item = cart.find(i => i.id === productId);
      const input = document.getElementById(`cart-note-${productId}`);

      if (!item || !input) return;

      item.note = input.value.trim().slice(0, 100);
      noteEditorItemId = null;
      renderCart();
    }

    function handleItemNoteKeydown(event, productId) {
      if (event.key === 'Enter') {
        event.preventDefault();
        saveItemNote(productId);
      }

      if (event.key === 'Escape') {
        closeItemNote();
      }
    }

    function renderCart() {
      const cartList = document.getElementById('cart-list');
      const cartTotal = document.getElementById('cart-total');

      if (cart.length === 0) {
        cartList.innerHTML = `
          <div class="flex flex-col items-center justify-center h-full text-gray-400 py-8">
            <p class="text-xs font-medium">Belum ada orderan</p>
          </div>
        `;
        cartTotal.innerText = 'RP 0';
        hidePaymentSection();
        return;
      }

      let html = '';
      let subtotal = 0;
      let totalCount = 0;

      cart.forEach(item => {
        const itemTotal = item.price * item.qty;
        subtotal += itemTotal;
        totalCount += item.qty;

        html += `
          <div class="py-2 flex items-start justify-between gap-2">
            <div class="min-w-0 flex-1">
              <h4 class="font-bold text-sm text-[#1A1208] font-heading leading-tight">${item.name}</h4>
              ${noteEditorItemId === item.id ? `
                <div class="mt-1.5 flex items-center gap-1.5">
                  <input
                    id="cart-note-${item.id}"
                    type="text"
                    maxlength="100"
                    value="${escapeHtml(item.note || '')}"
                    onkeydown="handleItemNoteKeydown(event, ${item.id})"
                    placeholder="Contoh: tanpa gula"
                    aria-label="Catatan untuk ${escapeHtml(item.name)}"
                    class="min-w-0 flex-1 rounded-md border border-[#CDBA9F] bg-white px-2 py-1 text-[11px] text-[#1A1208] focus:border-[#A08865] focus:ring-1 focus:ring-[#A08865]"
                  >
                  <button onclick="saveItemNote(${item.id})" class="rounded-md bg-[#A08865] px-2 py-1 text-[10px] font-bold text-white hover:bg-[#8d7554]">Simpan</button>
                  <button onclick="closeItemNote()" aria-label="Batal menulis catatan" class="rounded-md bg-[#E5E0DA] px-2 py-1 text-[10px] font-bold text-[#1A1208] hover:bg-[#D6CFC7]">Batal</button>
                </div>
              ` : `
                <button onclick="openItemNote(${item.id})" class="mt-0.5 flex max-w-full items-center gap-1 text-left text-[11px] font-medium text-[#A08865] hover:underline">
                  <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                  <span class="truncate">${item.note ? escapeHtml(item.note) : 'Tambahkan Catatan'}</span>
                </button>
              `}
            </div>

            <div class="flex flex-col items-end gap-1 shrink-0">
              <span class="text-xs font-bold text-[#1A1208] font-mono">${formatRupiah(itemTotal)}</span>
              
              <div class="flex items-center bg-[#E5E0DA] rounded-full px-1.5 py-0.5 gap-2">
                <button onclick="changeQty(${item.id}, -1)" class="w-4 h-4 bg-white text-[#1A1208] rounded-full flex items-center justify-center font-bold text-xs leading-none shadow-xs hover:bg-gray-100">-</button>
                <span class="text-xs font-bold text-[#1A1208] min-w-[10px] text-center">${item.qty}</span>
                <button onclick="changeQty(${item.id}, 1)" class="w-4 h-4 bg-[#A08865] text-white rounded-full flex items-center justify-center font-bold text-xs leading-none shadow-xs hover:bg-[#8c7453]">+</button>
              </div>
            </div>
          </div>
        `;
      });

      const ppn = Math.round(subtotal * 0.10);
      const totalTagihan = subtotal + ppn;

      cartList.innerHTML = html;
      cartTotal.innerText = formatRupiah(totalTagihan);

      // Update Angka di Rincian Tagihan
      document.getElementById('subtotal-label').innerText = `Sub Total (${totalCount} Item)`;
      document.getElementById('subtotal-val').innerText = formatRupiah(subtotal);
      document.getElementById('ppn-val').innerText = formatRupiah(ppn);
      document.getElementById('total-tagihan-val').innerText = formatRupiah(totalTagihan);
    }

    // Render Awal
    renderProducts();
    renderCart();
  </script>

</body>
</html>

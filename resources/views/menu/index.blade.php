<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Kasir - Coffe Ridho</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
  <style>
    :root {
      --color-bg-sidebar: #D8C29D;
      --color-orange: #E06328;
      --font-brand: 'Barlow', sans-serif;
    }
    body {
      font-family: var(--font-brand);
      background-color: #dcd5d5;
    }
    .font-heading {
      font-family: 'Space Mono', var(--font-brand), monospace;
    }
  </style>
</head>

<body class="h-screen w-screen overflow-hidden p-3 md:p-4 flex items-center justify-center text-[#1A1208]">

  <!-- Container Utama -->
  <div class="w-full h-full max-w-[1280px] bg-white rounded-[1rem] overflow-hidden shadow-2xl flex flex-col md:flex-row border border-gray-100 relative">

    <!-- ================= SIDEBAR ================= -->
    <aside class="w-[84px] h-full bg-[#D8C29D] flex flex-col items-center justify-between py-5 shrink-0 relative">
      
      <!-- LOGO -->
      <div class="w-[48px] h-[48px] flex items-center justify-center shrink-0">
        <img src="{{ asset('depan/logo.png') }}" alt="Logo Coffe Ridho" class="w-full h-full object-contain">
      </div>

      <!-- GRID / MENU (ACTIVE) -->
      <div class="relative group cursor-pointer shrink-0">
        <div class="w-[48px] h-[48px] bg-white rounded-[14px] flex items-center justify-center shadow-sm">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="#D8C29D">
            <rect x="3" y="3" width="8" height="8" rx="2" />
            <rect x="13" y="3" width="8" height="8" rx="2" />
            <rect x="3" y="13" width="8" height="8" rx="2" />
            <rect x="13" y="13" width="8" height="8" rx="2" />
          </svg>
        </div>
      </div>

      <!-- KASIR -->
      <button class="w-[48px] h-[48px] bg-white rounded-[14px] flex items-center justify-center shadow-sm hover:opacity-90 transition shrink-0">
        <svg width="26" height="26" viewBox="0 0 32 32" fill="#D8C29D">
          <rect x="11" y="6" width="10" height="6" rx="1" />
          <path d="M7 14 C7 13, 8 12, 9 12 L23 12 C24 12, 25 13, 25 14 L26 21 L6 21 Z" />
          <circle cx="10" cy="15" r="0.8" fill="white"/>
          <circle cx="13" cy="15" r="0.8" fill="white"/>
          <circle cx="16" cy="15" r="0.8" fill="white"/>
          <circle cx="10" cy="18" r="0.8" fill="white"/>
          <circle cx="13" cy="18" r="0.8" fill="white"/>
          <circle cx="16" cy="18" r="0.8" fill="white"/>
          <rect x="5" y="21" width="22" height="5" rx="1.5" />
        </svg>
      </button>

      <!-- RIWAYAT -->
      <button class="w-[48px] h-[48px] bg-white rounded-[14px] flex items-center justify-center shadow-sm hover:opacity-90 transition shrink-0">
        <svg width="26" height="26" viewBox="0 0 32 32" fill="none" stroke="#D8C29D" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M 8 16 A 8 8 0 1 1 12 23.5" />
          <polyline points="5,11 8,16 13,13" fill="#D8C29D" stroke="none"/>
          <polyline points="16,11 16,16 20,16" stroke-width="3"/>
        </svg>
      </button>

      <!-- PROFIL (LINK KE DASHBOARD) -->
      <a href="{{ route('dashboard') }}" class="w-[48px] h-[48px] bg-white rounded-[14px] flex items-center justify-center shadow-sm hover:opacity-90 transition shrink-0">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="#D8C29D">
          <circle cx="12" cy="7.5" r="4" />
          <path d="M4 19 C4 15.5, 7.5 14, 12 14 C16.5 14, 20 15.5, 20 19 Z" />
        </svg>
      </a>

      <!-- LOGOUT -->
      <button class="w-[48px] h-[48px] bg-[#FF6565] rounded-[14px] flex items-center justify-center shadow-sm hover:bg-red-600 transition text-white shrink-0">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M 9 21 H 5 A 2 2 0 0 1 3 19 V 5 A 2 2 0 0 1 5 3 H 9" />
          <polyline points="16 17 21 12 16 7" />
          <line x1="21" y1="12" x2="9" y2="12" />
        </svg>
      </button>

    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 p-5 md:p-6 flex flex-col overflow-hidden h-full">
      
      <!-- Header Atas -->
      <div class="flex items-center justify-between gap-4 mb-5 shrink-0">
        <div>
          <h1 class="text-2xl md:text-3xl font-extrabold font-heading text-[#1A1208] tracking-tight">Menu</h1>
          <p class="text-xs md:text-sm font-semibold text-[#1A1208]/80 mt-0.5">Silahkan Pilih Menu yang Anda Inginkan</p>
        </div>

        <div class="flex items-center gap-3 bg-[#E6DDD0] px-4 py-1.5 rounded-full shrink-0">
          <div class="w-8 h-8 rounded-full bg-[#8C7A6B] flex items-center justify-center text-white text-xs font-bold">I</div>
          <div class="text-xs leading-tight">
            <p class="font-bold text-[#1A1208]">Irfan</p>
            <p class="text-[#1A1208]/70 font-medium">Kasir Shift 1</p>
          </div>
        </div>
      </div>

      <!-- Area Layout Utama -->
      <div class="flex flex-col lg:flex-row gap-5 flex-1 min-h-0 overflow-hidden">
        
        <!-- KOLOM KIRI: PRODUK & FILTER -->
        <div class="flex-1 flex flex-col min-h-0">
          
          <!-- Tombol Filter -->
          <div class="flex items-center gap-2 mb-4 shrink-0 justify-end">
            <button class="px-5 py-2 rounded-xl bg-[#D8C29D] text-[#1A1208] font-bold text-xs shadow-sm">Semua</button>
            <button class="px-5 py-2 rounded-xl bg-[#E8D8C3] hover:bg-[#D8C29D]/70 text-[#1A1208] font-bold text-xs transition">Makanan</button>
            <button class="px-5 py-2 rounded-xl bg-[#E8D8C3] hover:bg-[#D8C29D]/70 text-[#1A1208] font-bold text-xs transition">Minuman</button>
          </div>

          <!-- Grid Card Produk -->
          <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 overflow-y-auto pr-1 pb-2">
          </div>
        </div>

        <!-- KOLOM KANAN: PENCARIAN & PANEL ORDER -->
        <div class="w-full lg:w-80 flex flex-col gap-4 shrink-0">
          
          <!-- Input Cari -->
          <div class="relative w-full shrink-0">
            <input type="text" placeholder="Cari..." class="w-full bg-[#D8C29D] text-[#1A1208] placeholder-[#1A1208]/60 pl-4 pr-10 py-2.5 rounded-xl text-xs font-medium focus:outline-none">
            <button class="absolute right-0 top-0 bottom-0 w-10 bg-[#E06328] rounded-r-xl flex items-center justify-center text-white">
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

            <!-- Footer Total & Bayar -->
            <div class="pt-3 border-t border-gray-200 shrink-0">
              <button class="w-full bg-[#E06328] hover:bg-[#c9521c] text-white font-bold py-3 px-4 rounded-xl flex items-center justify-between transition shadow-md">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-9.83-3.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1.003 1.003 0 0020 4H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.13 0-.25-.11-.25-.25z"/>
                </svg>
                <span id="cart-total" class="text-base tracking-wider font-heading font-extrabold">RP 0</span>
              </button>
            </div>

          </div>

        </div>

      </div>
    </main>

  </div>

  <!-- ================= MODAL EDIT DETAIL MENU ================= -->
  <div id="editMenuModal" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-50 flex items-center justify-center hidden p-3 md:p-4">
    <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl p-4 md:p-5 relative overflow-y-auto max-h-[92vh]">
      
      <!-- Tombol Close (X) -->
      <button type="button" onclick="closeEditModal()" class="absolute top-3.5 right-3.5 text-[#1A1208]/50 hover:text-[#1A1208] hover:bg-gray-100 rounded-lg p-1.5 transition flex items-center justify-center" title="Tutup">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <!-- Judul Modal -->
      <h2 class="text-lg md:text-xl font-extrabold font-heading text-[#1A1208] mb-3 pr-8">Edit Detail Menu</h2>

      <!-- Upload Foto Block -->
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
              Uploud foto
              <input type="file" class="hidden" accept="image/*">
            </label>
            <button type="button" class="bg-white hover:bg-gray-100 text-[#8B2626] border border-gray-200 px-2.5 py-1 rounded-md text-[11px] font-bold font-heading flex items-center gap-1 transition">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
              </svg>
              Hapus foto
            </button>
          </div>
        </div>
      </div>

      <!-- Form Inputs Row 1 -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 mb-3.5">
        <div>
          <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Nama produk</label>
          <input type="text" id="edit-name" class="w-full bg-[#D9D9D9] text-[#1A1208] font-bold px-3 py-1.5 rounded-lg text-xs focus:outline-none">
        </div>
        <div>
          <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Kategori Menu</label>
          <div class="relative">
            <select id="edit-category" class="w-full bg-[#D9D9D9] text-[#1A1208] font-bold px-3 py-1.5 rounded-lg text-xs focus:outline-none appearance-none cursor-pointer pr-7">
              <option value="Coffe">Coffe</option>
              <option value="Non-Coffe">Non-Coffe</option>
              <option value="Makanan">Makanan</option>
            </select>
            <div class="pointer-events-none absolute right-2.5 top-0 bottom-0 flex items-center text-[#1A1208]">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </div>
          </div>
        </div>
        <div>
          <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">SKU</label>
          <input type="text" id="edit-sku" class="w-full bg-[#D9D9D9] text-[#1A1208] font-bold px-3 py-1.5 rounded-lg text-xs focus:outline-none">
        </div>
      </div>

      <!-- Status Ketersediaan Stok -->
      <div class="mb-3.5">
        <label class="block text-xs font-extrabold font-heading text-[#1A1208] mb-1">Status Ketersediaan Stok</label>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
          <button type="button" onclick="setStatus('instock')" id="status-instock" class="bg-[#D9D9D9] text-[#1A1208] py-1.5 px-2 rounded-lg text-[11px] font-bold font-heading transition truncate">Tersedia (In Stock)</button>
          <button type="button" onclick="setStatus('out')" id="status-out" class="bg-[#D9D9D9] text-[#1A1208] py-1.5 px-2 rounded-lg text-[11px] font-bold font-heading transition truncate">Habis Sementara</button>
          <button type="button" onclick="setStatus('archive')" id="status-archive" class="bg-[#D9D9D9] text-[#1A1208] py-1.5 px-2 rounded-lg text-[11px] font-bold font-heading transition truncate">Arsipkan Menu</button>
        </div>
      </div>

      <!-- Stok Counter & Critical Alert -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
        <div>
          <label class="block text-[11px] font-semibold text-[#1A1208] mb-1">Jumlah Stok Hari ini (porsi/Cup)</label>
          <div class="flex items-center justify-between bg-[#D9D9D9] rounded-lg px-2 py-0.5">
            <button type="button" onclick="updateModalStock(-1)" class="w-6 h-6 rounded-full bg-white text-[#1A1208] font-bold text-xs flex items-center justify-center shadow-xs hover:bg-gray-100">-</button>
            <input type="number" id="edit-stock" value="40" class="w-full text-center bg-transparent font-extrabold font-heading text-sm text-[#1A1208] focus:outline-none py-1">
            <button type="button" onclick="updateModalStock(1)" class="w-6 h-6 rounded-full bg-[#A08865] text-white font-bold text-xs flex items-center justify-center shadow-xs hover:bg-[#8d7554]">+</button>
          </div>
        </div>

        <div>
          <label class="block text-[11px] font-semibold text-[#1A1208] mb-1">Batas peringatan Kritis (Min.Alert)</label>
          <input type="number" id="edit-min-alert" value="10" class="w-full bg-[#D9D9D9] text-center font-extrabold font-heading text-sm text-[#1A1208] py-1 rounded-lg focus:outline-none">
        </div>
      </div>

      <!-- Tombol Aksi bawah (Hapus & Simpan) -->
      <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-gray-100">
        <button type="button" onclick="deleteMenu()" class="bg-[#8B2626] hover:bg-red-800 text-white font-bold px-6 py-2 rounded-xl text-xs font-heading transition shadow-sm">Hapus</button>
        <button type="button" onclick="saveMenu()" class="bg-[#A88C52] hover:bg-[#937842] text-white font-bold px-6 py-2 rounded-xl text-xs font-heading transition shadow-sm">Simpan</button>
      </div>

    </div>
  </div>

  <!-- ================= MODAL NOTIFIKASI: BERHASIL DIHAPUS ================= -->
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
            Item <span id="deleted-item-name" class="font-bold text-[#A88C52]">kopi Qinta (SKU-3314)</span> berhasil dihapus
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

  <!-- ================= MODAL NOTIFIKASI: GAGAL TERSIMPAN / STOK HABIS ================= -->
  <div id="saveFailedModal" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-50 flex items-center justify-center hidden p-3">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-5 relative">
      <button type="button" onclick="closeModal('saveFailedModal')" class="absolute top-3.5 right-3.5 w-7 h-7 bg-gray-200 hover:bg-gray-300 text-[#1A1208] rounded-full flex items-center justify-center transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <div class="flex items-center gap-3.5 mb-5 pr-6">
        <div class="w-12 h-12 rounded-full bg-[#A88C52] shrink-0"></div>
        <div>
          <h3 class="font-extrabold font-heading text-[#1A1208] text-base md:text-lg">Tidak berhasil menambahkan Menu</h3>
          <p class="text-xs text-[#1A1208]/90 mt-0.5">
            Item <span id="failed-item-name" class="font-bold text-[#A88C52]">kopi Qinta (SKU-3314)</span> Stok sudah habis
          </p>
        </div>
      </div>

      <div class="flex items-center gap-2.5">
        <button type="button" onclick="closeModal('saveFailedModal')" class="flex-1 bg-[#D9D9D9] hover:bg-gray-300 text-[#1A1208] font-bold py-2.5 px-3 rounded-full text-xs font-heading flex items-center justify-center gap-2 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
          </svg>
          Kembali ke Menu
        </button>
        <button type="button" onclick="reopenEditModalFromFailed()" class="flex-1 bg-[#A88C52] hover:bg-[#937842] text-white font-bold py-2.5 px-3 rounded-full text-xs font-heading flex items-center justify-center gap-1.5 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
          Isi Stok Kembali
        </button>
      </div>
    </div>
  </div>

  <!-- Script Logika Keranjang & Modal Edit & Modal Popup Notifikasi -->
  <script>
    // Data Daftar Produk Initial
    let products = [
      { id: 1, name: "Expresso", price: 18000, category: "Coffe", sku: "EXP-001", status: "instock", stock: 40, minAlert: 10 },
      { id: 2, name: "Americano", price: 20000, category: "Coffe", sku: "AME-002", status: "instock", stock: 35, minAlert: 10 },
      { id: 3, name: "Susu Ridho", price: 22000, category: "Coffe", sku: "SEN-001", status: "instock", stock: 40, minAlert: 10 },
      { id: 4, name: "Cafe Latte", price: 23000, category: "Coffe", sku: "LAT-004", status: "instock", stock: 25, minAlert: 5 },
      { id: 5, name: "kopi Qinta", price: 25000, category: "Coffe", sku: "SKU-3314", status: "instock", stock: 15, minAlert: 5 },
      { id: 6, name: "Croissant", price: 15000, category: "Makanan", sku: "CRO-006", status: "instock", stock: 50, minAlert: 10 },
    ];

    let cart = [];
    let currentEditId = null;
    let selectedStatus = 'instock';
    let lastDeletedItem = null; // Menyimpan item yang baru dihapus untuk fungsi UNDO

    function formatRupiah(num) {
      return 'Rp ' + num.toLocaleString('id-ID');
    }

    function renderProducts() {
      const grid = document.getElementById('product-grid');
      grid.innerHTML = '';

      products.forEach(item => {
        grid.innerHTML += `
          <div class="bg-transparent flex flex-col">
            <div class="relative w-full h-24 sm:h-28 bg-[#D0D0D0] rounded-xl mb-2 flex items-center justify-center">
              <button onclick="openEditModal(${item.id})" class="absolute top-2 right-2 w-6 h-6 bg-[#C2C2C2] hover:bg-gray-400 rounded-full flex items-center justify-center text-[#1A1208] text-xs font-bold transition" title="Edit Detail Menu">⋮</button>
            </div>
            <div class="flex items-center justify-between">
              <div>
                <h4 class="font-bold text-sm text-[#1A1208] font-heading leading-tight">${item.name}</h4>
                <p class="text-xs font-bold text-[#1A1208]/90">${formatRupiah(item.price)}</p>
              </div>
              <button onclick="addToCart(${item.id})" class="w-7 h-7 bg-[#E06328] hover:bg-[#c9521c] text-white rounded-lg flex items-center justify-center font-bold text-base transition shadow-sm">+</button>
            </div>
          </div>
        `;
      });
    }

    // --- UTILITY MODAL CLOSER ---
    function closeModal(modalId) {
      document.getElementById(modalId).classList.add('hidden');
    }

    // --- EDIT MODAL FUNCTIONS ---
    function openEditModal(productId) {
      const p = products.find(prod => prod.id === productId);
      if (!p) return;

      currentEditId = productId;
      document.getElementById('edit-name').value = p.name;
      document.getElementById('edit-category').value = p.category || 'Coffe';
      document.getElementById('edit-sku').value = p.sku || 'SKU-000';
      document.getElementById('edit-stock').value = p.stock || 0;
      document.getElementById('edit-min-alert').value = p.minAlert || 10;
      document.getElementById('modal-photo-preview').innerText = p.name.substring(0, 4);

      setStatus(p.status || 'instock');
      document.getElementById('editMenuModal').classList.remove('hidden');
    }

    function closeEditModal() {
      document.getElementById('editMenuModal').classList.add('hidden');
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

    function updateModalStock(delta) {
      const input = document.getElementById('edit-stock');
      let val = parseInt(input.value) || 0;
      val += delta;
      if (val < 0) val = 0;
      input.value = val;
    }

    // --- FUNGSI SIMPAN DENGAN CEK STOK (NOTIFIKASI GAGAL TERSIMPAN) ---
    function saveMenu() {
      if (!currentEditId) return;

      const idx = products.findIndex(p => p.id === currentEditId);
      if (idx === -1) return;

      const newStock = parseInt(document.getElementById('edit-stock').value) || 0;
      const newName = document.getElementById('edit-name').value;
      const newSku = document.getElementById('edit-sku').value;

      // JIKA STOK HABIS (0) ATAU STATUS 'HABIS SEMENTARA' -> TAMPILKAN MODAL GAGAL
      if (newStock <= 0 || selectedStatus === 'out') {
        closeEditModal();
        document.getElementById('failed-item-name').innerText = `${newName} (${newSku})`;
        document.getElementById('saveFailedModal').classList.remove('hidden');
        return;
      }

      // JIKA BERHASIL (STOK > 0)
      products[idx].name = newName;
      products[idx].category = document.getElementById('edit-category').value;
      products[idx].sku = newSku;
      products[idx].stock = newStock;
      products[idx].minAlert = parseInt(document.getElementById('edit-min-alert').value) || 0;
      products[idx].status = selectedStatus;

      renderProducts();
      closeEditModal();
    }

    function reopenEditModalFromFailed() {
      closeModal('saveFailedModal');
      if (currentEditId) {
        document.getElementById('editMenuModal').classList.remove('hidden');
      }
    }

    // --- FUNGSI HAPUS DENGAN POPUP NOTIFIKASI BERHASIL DIHAPUS & UNDO ---
    function deleteMenu() {
      if (!currentEditId) return;

      const p = products.find(item => item.id === currentEditId);
      if (!p) return;

      lastDeletedItem = { ...p }; // Simpan data untuk dibatalkan jika diklik 'Batalkan'

      // Hapus dari array produk
      products = products.filter(item => item.id !== currentEditId);
      renderProducts();

      closeEditModal();

      // Tampilkan Modal Notifikasi Dihapus
      document.getElementById('deleted-item-name').innerText = `${p.name} (${p.sku})`;
      document.getElementById('deleteSuccessModal').classList.remove('hidden');
    }

    function undoDelete() {
      if (lastDeletedItem) {
        products.push(lastDeletedItem);
        renderProducts();
        lastDeletedItem = null;
      }
      closeModal('deleteSuccessModal');
    }

    // --- CART FUNCTIONS ---
    function addToCart(productId) {
      const product = products.find(p => p.id === productId);
      const existing = cart.find(i => i.id === productId);

      if (existing) {
        existing.qty += 1;
      } else {
        cart.push({ ...product, qty: 1 });
      }

      renderCart();
    }

    function changeQty(productId, delta) {
      const item = cart.find(i => i.id === productId);
      if (!item) return;

      item.qty += delta;

      if (item.qty <= 0) {
        cart = cart.filter(i => i.id !== productId);
      }

      renderCart();
    }

    function clearCart() {
      cart = [];
      renderCart();
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
        return;
      }

      let html = '';
      let total = 0;

      cart.forEach(item => {
        const itemTotal = item.price * item.qty;
        total += itemTotal;

        html += `
          <div class="py-2 flex items-center justify-between gap-2">
            <div>
              <h4 class="font-bold text-sm text-[#1A1208] font-heading leading-tight">${item.name}</h4>
              <button class="flex items-center gap-1 text-[11px] text-[#A08865] hover:underline mt-0.5 font-medium">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Tambahkan Catatan
              </button>
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

      cartList.innerHTML = html;
      cartTotal.innerText = 'RP ' + total.toLocaleString('id-ID');
    }

    // Render Initial
    renderProducts();
    renderCart();
  </script>

</body>
</html>
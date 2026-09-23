<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu & Kasir</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] antialiased">

    <div x-data="{
        sidebarOpen: false,
        activeCategory: 'semua',
        searchQuery: '',
        menuItems: [
            { id: 1, name: 'Expresso', price: 18000, category: 'minuman' },
            { id: 2, name: 'Americano', price: 20000, category: 'minuman' },
            { id: 3, name: 'Cappuccino', price: 22000, category: 'minuman' },
            { id: 4, name: 'Nasi Goreng', price: 25000, category: 'makanan' },
            { id: 5, name: 'Mie Goreng', price: 20000, category: 'makanan' },
            { id: 6, name: 'Matcha Latte', price: 23000, category: 'minuman' },
        ],
        cart: [],
        addToCart(item) {
            let found = this.cart.find(c => c.id === item.id);
            if (found) { found.qty++; } 
            else { this.cart.push({ id: item.id, name: item.name, price: item.price, qty: 1 }); }
        },
        increaseQty(i) { this.cart[i].qty++; },
        decreaseQty(i) {
            if (this.cart[i].qty > 1) { this.cart[i].qty--; } 
            else { this.cart.splice(i, 1); }
        },
        clearCart() { this.cart = []; },
        get totalPrice() { return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0); },
        get filteredItems() {
            return this.menuItems.filter(item => {
                let matchCat = this.activeCategory === 'semua' || item.category === this.activeCategory;
                let matchSearch = item.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                return matchCat && matchSearch;
            });
        },
        formatRupiah(num) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(num); }
    }" class="flex h-screen overflow-hidden font-sans">

        <!-- 1. PANGGIL SIDEBAR KIRI -->
        <x-sidebar />

        <!-- 2. KONTEN MENU UTAMA -->
        <main class="flex-1 flex flex-col overflow-y-auto p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Menu</h1>
                    <p class="text-gray-600 font-medium mt-1">Silahkan Pilih Menu yang Anda Ingin</p>
                </div>
                <div class="flex items-center gap-3 bg-[#EBE3D5] px-4 py-2 rounded-full shadow-sm">
                    <div class="w-9 h-9 rounded-full bg-gray-600 flex items-center justify-center text-white text-xs font-bold">IR</div>
                    <div class="text-left text-xs leading-tight pr-2">
                        <p class="font-bold text-gray-800">Irfan</p>
                        <p class="text-gray-500">Kasir Shift 1</p>
                    </div>
                </div>
            </div>

            <!-- Filter & Search -->
            <div class="flex justify-between items-center mb-8 gap-4">
                <div class="flex gap-3">
                    <button @click="activeCategory = 'semua'" :class="activeCategory === 'semua' ? 'bg-[#DEC396] text-gray-900' : 'bg-[#EBE3D5] text-gray-700'" class="px-6 py-2.5 font-bold rounded-xl transition shadow-sm">Semua</button>
                    <button @click="activeCategory = 'makanan'" :class="activeCategory === 'makanan' ? 'bg-[#DEC396] text-gray-900' : 'bg-[#EBE3D5] text-gray-700'" class="px-6 py-2.5 font-bold rounded-xl transition shadow-sm">Makanan</button>
                    <button @click="activeCategory = 'minuman'" :class="activeCategory === 'minuman' ? 'bg-[#DEC396] text-gray-900' : 'bg-[#EBE3D5] text-gray-700'" class="px-6 py-2.5 font-bold rounded-xl transition shadow-sm">Minuman</button>
                </div>

                <div class="relative w-80">
                    <input x-model="searchQuery" type="text" placeholder="Cari menu..." class="w-full bg-[#EBE3D5] border-none rounded-xl py-2.5 pl-4 pr-12 text-sm text-gray-800 focus:ring-2 focus:ring-[#E0632A] placeholder-gray-500">
                    <div class="absolute right-0 top-0 bottom-0 bg-[#E0632A] px-3.5 rounded-r-xl flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                </div>
            </div>

            <!-- Grid Menu -->
            <div class="grid grid-cols-3 gap-6">
                <template x-for="item in filteredItems" :key="item.id">
                    <div class="bg-transparent rounded-2xl flex flex-col justify-between">
                        <div class="relative bg-[#DDD3C4] h-40 rounded-2xl mb-3 flex items-start justify-end p-2">
                            <button class="text-gray-600 hover:text-gray-900">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            </button>
                        </div>
                        <div class="flex justify-between items-end">
                            <div>
                                <h3 class="font-bold text-gray-900 text-base" x-text="item.name"></h3>
                                <p class="text-gray-800 font-semibold text-sm" x-text="formatRupiah(item.price)"></p>
                            </div>
                            <button @click="addToCart(item)" class="w-9 h-9 bg-[#E0632A] hover:bg-[#c85220] active:scale-95 text-white rounded-lg flex items-center justify-center font-bold text-xl shadow-sm transition">+</button>
                        </div>
                    </div>
                </template>
            </div>
        </main>

        <!-- 3. PANGGIL PANEL ORDER KANAN -->
        <x-order-panel />

    </div>
</body>
</html>
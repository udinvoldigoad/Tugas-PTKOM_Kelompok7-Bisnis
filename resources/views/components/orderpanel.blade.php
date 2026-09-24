<aside class="w-96 bg-white rounded-3xl my-6 mr-6 p-6 shadow-sm flex flex-col justify-between shrink-0 border border-gray-100">
    <div>
        <!-- Header Keranjang -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <h2 class="text-2xl font-bold text-gray-900">Order</h2>
                <span class="bg-[#DEC396] text-white font-bold text-xs px-2.5 py-1 rounded-md">#TRX-001</span>
            </div>
            <button @click="clearCart()" class="p-2 bg-[#A83232] text-white rounded-xl hover:bg-red-800 transition" title="Kosongkan Keranjang">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>

        <template x-if="cart.length === 0">
            <div class="text-center py-16 text-gray-400 font-medium">
                Belum ada menu yang dipilih
            </div>
        </template>

        <div class="space-y-4 max-h-[calc(100vh-280px)] overflow-y-auto pr-1">
            <template x-for="(cartItem, index) in cart" :key="cartItem.id">
                <div class="border-b border-gray-100 pb-3">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="font-bold text-gray-900" x-text="cartItem.name"></h4>
                        <span class="font-bold text-sm text-gray-800" x-text="formatRupiah(cartItem.price * cartItem.qty)"></span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <button class="text-xs text-[#DEC396] font-semibold flex items-center gap-1 hover:underline">
                            📝 Tambahkan Catatan
                        </button>
                        <div class="flex items-center gap-2 bg-[#EBE3D5] px-2 py-1 rounded-lg">
                            <button @click="decreaseQty(index)" class="w-5 h-5 bg-gray-300 text-gray-800 rounded flex items-center justify-center font-bold text-xs hover:bg-gray-400">-</button>
                            <span class="font-bold text-xs px-1 text-gray-800" x-text="cartItem.qty"></span>
                            <button @click="increaseQty(index)" class="w-5 h-5 bg-[#DEC396] text-gray-800 rounded flex items-center justify-center font-bold text-xs hover:bg-amber-300">+</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div class="pt-4">
        <button class="w-full bg-[#E0632A] hover:bg-[#c85220] text-white font-black text-lg py-4 rounded-xl shadow-md tracking-wider transition uppercase" x-text="formatRupiah(totalPrice)"></button>
    </div>
</aside>
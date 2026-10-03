<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->string('nama_menu')->nullable()->after('menu_id');
            $table->decimal('harga_satuan', 12, 2)->nullable()->after('jumlah');
        });

        DB::table('detail_transaksis')
            ->select(['id', 'menu_id', 'jumlah', 'subtotal'])
            ->orderBy('id')
            ->chunkById(100, function ($details): void {
                $menus = DB::table('menus')
                    ->whereIn('id', $details->pluck('menu_id'))
                    ->get(['id', 'nama_menu'])
                    ->keyBy('id');

                foreach ($details as $detail) {
                    DB::table('detail_transaksis')
                        ->where('id', $detail->id)
                        ->update([
                            'nama_menu' => $menus->get($detail->menu_id)?->nama_menu,
                            'harga_satuan' => $detail->jumlah > 0
                                ? round((float) $detail->subtotal / (int) $detail->jumlah, 2)
                                : null,
                        ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->dropColumn(['nama_menu', 'harga_satuan']);
        });
    }
};

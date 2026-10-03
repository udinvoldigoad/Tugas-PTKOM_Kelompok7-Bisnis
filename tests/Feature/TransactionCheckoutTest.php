<?php

namespace Tests\Feature;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use RuntimeException;
use Tests\TestCase;

class TransactionCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_cashier_can_store_cart_as_one_transaction(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu();
        $cart = [
            $menu->id => [
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'jumlah' => 2,
                'harga' => 1,
                'subtotal' => 2,
            ],
        ];

        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->postJson(route('transactions.store'), [
                'metode_pembayaran' => 'qris',
                'idempotency_key' => Str::uuid()->toString(),
            ])
            ->assertCreated()
            ->assertJsonPath('message', 'Transaksi berhasil disimpan.')
            ->assertSessionMissing('cart');

        $this->assertDatabaseHas('transaksis', [
            'user_id' => $user->id,
            'total_harga' => 48400,
            'metode_pembayaran' => 'qris',
        ]);
        $this->assertDatabaseHas('detail_transaksis', [
            'menu_id' => $menu->id,
            'nama_menu' => 'Kopi Susu',
            'jumlah' => 2,
            'harga_satuan' => 22000,
            'subtotal' => 44000,
        ]);
    }

    public function test_transaction_is_not_partially_stored_when_a_menu_is_unavailable(): void
    {
        $user = User::factory()->create();
        $availableMenu = $this->createMenu();
        $unavailableMenu = $this->createMenu([
            'nama_menu' => 'Lemon Tea',
            'status_ketersediaan' => 'habis',
        ]);
        $cart = [
            $availableMenu->id => ['jumlah' => 1],
            $unavailableMenu->id => ['jumlah' => 1],
        ];

        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->postJson(route('transactions.store'), [
                'metode_pembayaran' => 'cash',
                'idempotency_key' => Str::uuid()->toString(),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('cart');

        $this->assertDatabaseCount('transaksis', 0);
        $this->assertDatabaseCount('detail_transaksis', 0);
        $this->assertEquals($cart, session('cart'));
    }

    public function test_repeated_idempotency_key_returns_the_original_transaction(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu();
        $cart = [
            $menu->id => [
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'jumlah' => 1,
                'harga' => $menu->harga,
                'subtotal' => $menu->harga,
            ],
        ];

        $idempotencyKey = Str::uuid()->toString();
        $firstResponse = $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->postJson(route('transactions.store'), [
                'metode_pembayaran' => 'cash',
                'idempotency_key' => $idempotencyKey,
            ])
            ->assertCreated();

        $this->postJson(route('transactions.store'), [
            'metode_pembayaran' => 'cash',
            'idempotency_key' => $idempotencyKey,
        ])->assertOk()
            ->assertJsonPath('replayed', true)
            ->assertJsonPath('transaksi.id', $firstResponse->json('transaksi.id'));

        $this->postJson(route('transactions.store'), [
            'metode_pembayaran' => 'cash',
            'idempotency_key' => Str::uuid()->toString(),
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('cart');

        $this->assertDatabaseCount('transaksis', 1);
        $this->assertDatabaseCount('detail_transaksis', 1);
    }

    public function test_database_failure_rolls_back_transaction_and_keeps_cart(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu();
        $cart = [
            $menu->id => [
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'jumlah' => 1,
                'harga' => $menu->harga,
                'subtotal' => $menu->harga,
            ],
        ];
        DetailTransaksi::creating(function (): void {
            throw new RuntimeException('Simulasi kegagalan database.');
        });

        try {
            $this->actingAs($user)
                ->withSession(['cart' => $cart])
                ->postJson(route('transactions.store'), [
                    'metode_pembayaran' => 'cash',
                    'idempotency_key' => Str::uuid()->toString(),
                ])
                ->assertServerError();
        } finally {
            DetailTransaksi::flushEventListeners();
        }

        $this->assertDatabaseCount('transaksis', 0);
        $this->assertDatabaseCount('detail_transaksis', 0);
        $this->assertEquals($cart, session('cart'));
    }

    public function test_transaction_requires_a_supported_payment_method(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu();

        $this->actingAs($user)
            ->withSession(['cart' => [$menu->id => ['jumlah' => 1]]])
            ->postJson(route('transactions.store'), [
                'metode_pembayaran' => 'transfer',
                'idempotency_key' => Str::uuid()->toString(),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('metode_pembayaran');

        $this->assertDatabaseCount('transaksis', 0);
    }

    public function test_transaction_rejects_a_total_that_exceeds_database_capacity(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu(['harga' => 9090909090]);

        $this->actingAs($user)
            ->withSession(['cart' => [$menu->id => ['jumlah' => 2]]])
            ->postJson(route('transactions.store'), [
                'metode_pembayaran' => 'cash',
                'idempotency_key' => Str::uuid()->toString(),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('cart');

        $this->assertDatabaseCount('transaksis', 0);
        $this->assertDatabaseCount('detail_transaksis', 0);
    }

    public function test_transaction_requires_an_idempotency_key(): void
    {
        $user = User::factory()->create();
        $menu = $this->createMenu();

        $this->actingAs($user)
            ->withSession(['cart' => [$menu->id => ['jumlah' => 1]]])
            ->postJson(route('transactions.store'), ['metode_pembayaran' => 'cash'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('idempotency_key');

        $this->assertDatabaseCount('transaksis', 0);
    }

    public function test_different_cashiers_can_use_the_same_idempotency_key(): void
    {
        $firstCashier = User::factory()->create();
        $secondCashier = User::factory()->create();
        $menu = $this->createMenu();
        $idempotencyKey = Str::uuid()->toString();
        $cart = [$menu->id => ['jumlah' => 1]];

        $this->actingAs($firstCashier)
            ->withSession(['cart' => $cart])
            ->postJson(route('transactions.store'), [
                'metode_pembayaran' => 'cash',
                'idempotency_key' => $idempotencyKey,
            ])
            ->assertCreated();

        $this->actingAs($secondCashier)
            ->withSession(['cart' => $cart])
            ->postJson(route('transactions.store'), [
                'metode_pembayaran' => 'cash',
                'idempotency_key' => $idempotencyKey,
            ])
            ->assertCreated();

        $this->assertDatabaseCount('transaksis', 2);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createMenu(array $attributes = []): Menu
    {
        return Menu::create(array_merge([
            'nama_menu' => 'Kopi Susu',
            'kategori' => 'Kopi',
            'harga' => 22000,
            'status_ketersediaan' => 'tersedia',
        ], $attributes));
    }
}

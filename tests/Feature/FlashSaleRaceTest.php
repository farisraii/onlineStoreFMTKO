<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FlashSaleRaceTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testFlashSaleRaceCondition()
    {
        $this->browser(function (Browser $first, Browser $second) {
            // Dua pengguna mencoba membeli item secara bersamaan
            $first->visit('/flash-sale')
                ->click('@buy-button')
                ->waitForText('Pembelian berhasil atau gagal');

            $second->visit('/flash-sale')
                ->click('@buy-button')
                ->waitForText('Pembelian berhasil atau gagal');

            // Menguji bahwa hanya satu pengguna yang berhasil
            $first->assertSee('Pembelian berhasil')->assertDontSee('Kuantitas habis');
            $second->assertSee('Kuantitas habis')->assertDontSee('Pembelian berhasil');
        });
    }
}

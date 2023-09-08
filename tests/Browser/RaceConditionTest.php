<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RaceConditionTest extends DuskTestCase
{
    /**
     * Test kemampuan API untuk menangani kondisi perlombaan.
     *
     * @return void
     */
    public function testRaceConditionHandling()
    {
        $this->browse(function (Browser $browser) {
            // Mulai dua proses Dusk secara bersamaan
            $browser1 = clone $browser;
            $browser2 = clone $browser;

            // Browser 1 mengirim permintaan pertama
            $browser1->visit('/api/endpoint')
                ->waitForText('Response dari permintaan pertama')
                ->assertSee('Response dari permintaan pertama');

            // Browser 2 mengirim permintaan kedua pada saat yang bersamaan
            $browser2->visit('/api/endpoint')
                ->waitForText('Response dari permintaan kedua')
                ->assertSee('Response dari permintaan kedua');
        });
    }
}

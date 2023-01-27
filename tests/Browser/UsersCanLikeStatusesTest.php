<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Status;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanLikeStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

     /**
     * @test
     *
     * @throws Exception
     */
    public function guest_users_cannot_like_statuses()
    {
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use ($status) {
            $browser
                ->visit('/')
                ->waitForText($status->body)
                ->press('@like-btn')
                ->assertPathIs('/login');
        });
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function users_can_like_and_unlike_statuses()
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use($user,$status) {
            $browser
                ->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->press('@like-btn')
                ->waitForText('TE GUSTA')
                ->assertSee('TE GUSTA')
                ->press('@unlike-btn')
                ->waitForText('ME GUSTA')
                ->assertSee('ME GUSTA');
        });
    }


}

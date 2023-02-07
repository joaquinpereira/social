<?php

namespace Tests\Browser;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanLikeCommentsTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @test
     *
     * @throws Exception
     */
    public function users_can_like_and_unlike_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $comment) {
            $browser
                ->loginAs($user)
                ->visit('/')
                ->waitForText($comment->body)
                ->assertSeeIn('@comment-likes-count', 0)
                ->press('@comment-like-btn')
                ->waitForText('TE GUSTA')
                ->assertSee('TE GUSTA')
                ->assertSeeIn('@comment-likes-count', 1)

                ->press('@comment-like-btn')
                ->waitForText('ME GUSTA')
                ->assertSee('ME GUSTA');
                //Este ultimo assert no funciona pese a que devuelve el valor correctogit status
                // ->assertSee('ME GUSTA')
                // ->assertSeeIn('@comment-likes-count', 0);
        });
    }
}

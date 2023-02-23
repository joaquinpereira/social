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

    /** @test */
    public function users_can_like_and_unlike_comments()
    {
        $user = User::factory()->create()->first();
        $comment = Comment::factory()->create()->first();

        $this->withoutExceptionHandling();

        $this->browse(function (Browser $browser) use ($user, $comment) {

            $browser
                ->loginAs($user)
                ->visit('/')
                ->waitForText($comment->body)
                ->assertSeeIn('@comment-likes-count', 0)
                ->press('comment-like-btn')
                ->waitForText('TE GUSTA')
                ->assertSee('TE GUSTA')
                ->assertSeeIn('@comment-likes-count', 1)

                ->press('comment-like-btn')
                ->waitForText('ME GUSTA')
                ->assertSee('ME GUSTA')
                ->pause(800)
                ->assertSeeIn('@comment-likes-count', 0);
        });
    }

    /** @test */
    public function users_can_like_and_unlike_comments_in_real_time()
    {
        $user = User::factory()->create()->first();
        $comment = Comment::factory()->create()->first();

        $this->withoutExceptionHandling();

        $this->browse(function (Browser $browser1,Browser $browser2) use ($user, $comment) {
            $browser1->visit('/');

            $browser2
                ->loginAs($user)
                ->visit('/')
                ->waitForText($comment->body)
                ->assertSeeIn('@comment-likes-count', 0)
                ->press('comment-like-btn')
                ->waitForText('TE GUSTA')
            ;

            $browser1->pause(800)->assertSeeIn('@comment-likes-count', 1);

            $browser2
                ->press('comment-like-btn')
                ->waitForText('ME GUSTA')
            ;

            $browser1->pause(800)->assertSeeIn('@comment-likes-count', 0);
        });
    }
}

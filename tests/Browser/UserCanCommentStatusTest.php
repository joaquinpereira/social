<?php

namespace Tests\Browser;

use App\Models\Comment;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserCanCommentStatusTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function users_can_see_all_comments()
    {
        $status = Status::factory()->create();
        $comments = Comment::factory(2)->create(['status_id' => $status->id]);

        $this->browse(function (Browser $browser) use($comments, $status){
            $comment = "Mi primer comentario";
            $browser
                ->visit('/')
                ->waitForText($status->body);

            foreach($comments as $comment){
                $browser->assertSee($comment->body)
                    ->assertSee($comment->user->name);
            }
        });
    }

    /**
     * @test
     */
    public function authenticated_users_can_comment_statuses()
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use($user, $status){
            $comment = "Mi primer comentario";
            $browser
                ->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->type('comment', $comment)
                ->press('@comment-btn')
                ->waitForText($comment)
                ->assertSee($comment);
        });
    }
}

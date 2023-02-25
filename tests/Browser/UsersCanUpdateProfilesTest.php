<?php

namespace Tests\Browser;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanUpdateProfilesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_users_can_not_update_and_store_the_avatar_file()
    {
        $user = User::factory()->create()->first();

        $this->browse(function (Browser $browser) use($user)
        {
            $browser
                ->visit("/@{$user->name}")
                ->assertSee($user->name)
                ->assertDontSeeLink('#open_dialog_update_picture_profile')
            ;
        });

    }

    /** @test */
    public function an_authenticated_user_can_update_and_store_the_avatar_file()
    {
        Storage::fake();

        $user = User::factory()->create()->first();
        $statuses = Status::factory(2)->create(['user_id' => $user->id]);

        $avatar = fake()->file(
            storage_path('app/public/users_for_test'),
            storage_path('app/public/users'),
            true
        );

        $this->browse(function (Browser $browser) use($user, $statuses, $avatar)
        {
            $browser->loginAs($user)
                ->visit("/@{$user->name}")
                ->waitForText($statuses->first()->body)
                ->assertSee($user->name)
                ->assertPresent('#open_dialog_update_picture_profile')
                ->press('#open_dialog_update_picture_profile')
                ->whenAvailable('#dialog_update_picture_profile', function ($modal) use($user, $avatar){
                    $modal->assertSee('Actualizar foto de perfil')
                        ->attach('avatar', $avatar)
                    ;
                })
                ->clickAndWaitForReload('#update_picture_profile')
                ->assertAttributeContains('#picture_profile', 'src', $user->fresh()->avatar)
            ;
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $user = User::factory()->create(['email'=>'Joaquin@email.com','name'=>'Joaquin']);

        Status::factory(3)->create(['user_id' => $user->id]);
        Status::factory(10)->create();
    }
}

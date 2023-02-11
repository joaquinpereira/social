<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FriendShip>
 */
class FriendShipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'recipient_id' => function(){
                return User::factory()->create();
            },
            'sender_id' => function(){
                return User::factory()->create();
            },
        ];
    }
}

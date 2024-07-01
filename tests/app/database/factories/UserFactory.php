<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ShabuShabu\Uid\Tests\App\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
        ];
    }
}

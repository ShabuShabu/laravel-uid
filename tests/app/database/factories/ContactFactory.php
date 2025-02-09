<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ShabuShabu\Uid\Tests\App\Models\Contact;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
        ];
    }
}

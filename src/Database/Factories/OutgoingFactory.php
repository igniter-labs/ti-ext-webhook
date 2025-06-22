<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Database\Factories;

use Igniter\Flame\Database\Factories\Factory;
use IgniterLabs\Webhook\Models\Outgoing;
use Override;

class OutgoingFactory extends Factory
{
    protected $model = Outgoing::class;

    #[Override]
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'url' => $this->faker->url(),
            'events' => [],
            'config_data' => [],
            'is_active' => true,
        ];
    }
}

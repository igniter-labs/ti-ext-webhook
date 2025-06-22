<?php

declare(strict_types=1);

use Igniter\User\Models\User;
use SamPoyigi\Testbench\TestCase;

uses(TestCase::class)->in(__DIR__);

function actingAsSuperUser(?User $user = null)
{
    return test()->actingAs($user ?? User::factory()->superUser()->create(), 'igniter-admin');
}

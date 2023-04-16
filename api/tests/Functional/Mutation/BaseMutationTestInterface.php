<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mutation;

interface BaseMutationTestInterface
{
    public function should_not_create_object_if_not_authenticated(): void;

    public function should_not_update_object_if_not_authenticated(): void;

    public function should_not_delete_object_if_not_authenticated(): void;
}

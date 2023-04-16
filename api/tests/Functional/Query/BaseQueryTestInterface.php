<?php

declare(strict_types=1);

namespace App\Tests\Functional\Query;

interface BaseQueryTestInterface
{
    public function should_not_get_object_if_not_authenticated(): void;

    public function should_not_get_object_list_if_not_authenticated(): void;

    public function should_get_object_list(): void;

    public function should_get_object_list_with_limit(): void;

    public function should_get_object_list_ordered_by_field(): void;

    public function should_get_object_by_id(): void;
}

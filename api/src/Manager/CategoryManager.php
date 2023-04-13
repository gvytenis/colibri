<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Category;
use Overblog\GraphQLBundle\Definition\Argument;

final class CategoryManager
{
    public function create(Argument $arguments): Category
    {
        return $this->createOrUpdate($arguments);
    }

    public function update(Argument $arguments, Category $category): Category
    {
        return $this->createOrUpdate($arguments, $category);
    }

    private function createOrUpdate(Argument $arguments, Category $category = null): Category
    {
        $input = $arguments->offsetGet('category');
        assert(is_array($input));

        return ($category ?? new Category())
            ->setName($input['name']);
    }
}

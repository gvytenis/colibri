<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Author;
use Overblog\GraphQLBundle\Definition\Argument;

final class AuthorManager
{
    public function create(Argument $arguments): Author
    {
        return $this->createOrUpdate($arguments);
    }

    public function update(Argument $arguments, Author $author): Author
    {
        return $this->createOrUpdate($arguments, $author);
    }

    private function createOrUpdate(Argument $arguments, Author $author = null): Author
    {
        $input = $arguments->offsetGet('author');
        assert(is_array($input));

        return ($author ?? new Author())
            ->setName($input['name']);
    }
}

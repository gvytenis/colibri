<?php

declare(strict_types=1);

namespace App\Service;

use Overblog\GraphQLBundle\Definition\Argument;

class CollectionArgumentProvider
{
    private const DEFAULT_LIMIT = 10;

    private const DEFAULT_SORT_COLUMN = 'id';

    private const DEFAULT_SORT_CRITERIA = 'desc';

    public function provide(Argument $arguments): array
    {
        $limit = $arguments['limit'] ?? self::DEFAULT_LIMIT;
        $orderBy = $arguments['orderBy'] ?? self::DEFAULT_SORT_COLUMN;
        $criteria = $arguments['criteria'] ?? self::DEFAULT_SORT_CRITERIA;

        return [$limit, $orderBy, $criteria];
    }
}

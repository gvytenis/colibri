<?php

declare(strict_types=1);

namespace App\Service;

use Overblog\GraphQLBundle\Definition\Builder\MappingInterface;

class MutationResponse implements MappingInterface
{
    public function toMappingDefinition(array $config): array
    {
        return [
            'code' => [
                'description' => 'Response code',
                'type' => 'Int!',
            ],
            'message' => [
                'description' => 'Response message',
                'type' => 'String!',
            ],
        ];
    }
}

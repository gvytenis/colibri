<?php

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => [
        'all' => true,
    ],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => [
        'all' => true,
    ],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => [
        'all' => true,
    ],
    Overblog\GraphQLBundle\OverblogGraphQLBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\TwigBundle\TwigBundle::class => [
        'all' => true,
    ],
    Overblog\GraphiQLBundle\OverblogGraphiQLBundle::class => [
        'dev' => true,
    ],
    Symfony\Bundle\MonologBundle\MonologBundle::class => [
        'all' => true,
    ],
    Sentry\SentryBundle\SentryBundle::class => [
        'prod' => true,
    ],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => [
        'all' => true,
    ],
    Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle::class => [
        'all' => true,
    ],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    Nelmio\CorsBundle\NelmioCorsBundle::class => [
        'all' => true,
    ],
];

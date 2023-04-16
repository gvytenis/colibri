<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\InMemoryUser;
use function json_decode;

class ApiTestCase extends WebTestCase
{
    use FixtureTrait;

    protected ?KernelBrowser $client;

    protected ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        /** @var Registry $registry */
        $registry = static::getContainer()->get('doctrine');
        /** @var EntityManagerInterface $manager */
        $manager = $registry->getManager();

        $this->entityManager = $manager;
        assert($this->entityManager instanceof EntityManagerInterface);

        $this->recreateDatabase($this->entityManager)
            ->loadFixtures($this->entityManager);
    }

    protected function tearDown(): void
    {
        assert($this->entityManager instanceof EntityManagerInterface);
        $this->cleanupAfterTest($this->entityManager);

        $this->entityManager = null;
        $this->client = null;

        parent::tearDown();
    }

    public function sendGetRequest(string $uri, array $parameters = []): ?Crawler
    {
        return $this->client?->request(Request::METHOD_GET, $uri, $parameters);
    }

    public function sendPostRequest(string $uri, array $parameters = []): ?Crawler
    {
        return $this->client?->request(Request::METHOD_POST, $uri, $parameters);
    }

    public function getArrayResponse(): array
    {
        $content = $this->client?->getResponse()->getContent();

        return (array) json_decode((string) $content, true);
    }

    public function assertStatusCodeSuccess(): void
    {
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function assertStatusCodeUnauthorized(): void
    {
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function assertStatusCodeForbidden(): void
    {
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function assertStatusCodeBadRequest(): void
    {
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    protected function loginAsUser(): void
    {
        $this->setUser('user', 'user', ['ROLE_USER']);
    }

    protected function loginAsAdmin(): void
    {
        $this->setUser('admin', 'admin', ['ROLE_ADMIN']);
    }

    protected function setUser(string $username, string $password, array $roles): void
    {
        /** @var JWTTokenManagerInterface $tokenManager */
        $tokenManager = static::$kernel->getContainer()->get('lexik_jwt_authentication.jwt_manager');

        $token = $tokenManager->create(new InMemoryUser($username, $password, $roles));
        $this->client?->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));
    }
}

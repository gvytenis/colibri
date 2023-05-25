<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class BasicAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserProviderInterface $userProvider,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $credentials = $request->toArray();

        if ($credentials['username'] === null || $credentials['password'] === null) {
            throw new AuthenticationException('Bad credentials.');
        }

        $user = $this->userRepository->findOneBy(['username' => $credentials['username']]);
        if (null === $user) {
            throw new AuthenticationException('Bad credentials.');
        }

        if (!$this->userPasswordHasher->isPasswordValid($user, $credentials['password'])) {
            throw new AuthenticationException('Bad credentials.');
        }

        return new SelfValidatingPassport($this->createUserBadge($credentials));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(status: Response::HTTP_UNAUTHORIZED);
    }

    private function createUserBadge(array $credentials): UserBadge
    {
        return new UserBadge($credentials['username'], function (string $username) {
            return $this->userProvider->loadUserByIdentifier($username);
        });
    }
}

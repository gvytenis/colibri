<?php

declare(strict_types=1);

namespace App\Controller;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route(path: '/api/login', name: 'login')]
class LoginController extends AbstractController
{
    public function __construct(
        private readonly int $ttl,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly JWTTokenManagerInterface $jwtManager,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (! $user instanceof UserInterface) {
            throw new UserNotFoundException('User not found.');
        }

        if (! $this->authorizationChecker->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY, $user)) {
            throw new AccessDeniedException('Access Denied: user is not authenticated fully.');
        }

        return $this->createResponse($this->jwtManager->create($user), $this->ttl);
    }

    public function createResponse(string $token, int $ttl): JsonResponse
    {
        return new JsonResponse([
            'token' => $token,
            'type' => 'bearer',
            'expires' => $ttl,
            'validity' => (new DateTime())
                ->add(new DateInterval(sprintf('PT%dS', $ttl)))
                ->format(DateTimeInterface::ATOM),
        ]);
    }
}

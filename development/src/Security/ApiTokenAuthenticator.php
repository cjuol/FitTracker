<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization') || $request->headers->has('X-AUTH-TOKEN');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $token = $this->getTokenFromRequest($request);

        return new SelfValidatingPassport(new UserBadge($token, function (string $token) {
            return $this->userRepository->findOneBy(['apiToken' => $token]);
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    private function getTokenFromRequest(Request $request): string
    {
        $authHeader = $request->headers->get('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            return trim(substr($authHeader, 7));
        }

        return (string) $request->headers->get('X-AUTH-TOKEN', '');
    }
}
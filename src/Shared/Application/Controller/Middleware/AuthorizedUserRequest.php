<?php

declare(strict_types=1);

namespace App\Shared\Application\Controller\Middleware;

use App\User\Domain\Repository\UserRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

#[AsEventListener(event: 'kernel.request')]
final class AuthorizedUserRequest
{
    public function __construct(
        private UserRepository $user_repository,
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        $token = str_replace(
            'token ',
            '',
            $event->getRequest()->headers->get('Authorization') ?? '',
        );

        if ($token === '') {
            throw new UnauthorizedHttpException('');
        }

        $id = $this->user_repository->getByToken($token)?->id ?? throw new UnauthorizedHttpException('');
        $event->getRequest()->headers->set('X_USER_ID', (string) $id);
    }
}

<?php

namespace App\Listeners;

use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Illuminate\Auth\Events\Login;
use Illuminate\Events\Dispatcher;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;

class UserEventSubscriber
{
    public function __construct(
        protected LoggerInterface $logger,
        protected Request $request
    ) {}

    function handleLogin(Login $event): void
    {
        $this->logger->info('User logged in', ['user_email' => $event->user->email, 'ip' => $this->request->getClientIp()]);
    }    

    public function handleLogout(Logout $event): void
    {
        $this->logger->info('User logged out', ['user_email' => $event->user->email, 'ip' => $this->request->getClientIp()]);
    }

    public function handleRegistered(Registered $event): void
    {
        $this->logger->info('User registered', ['user_email' => $event->user->email, 'ip' => $this->request->getClientIp()]);
    }

    public function handleFailed(Failed $event): void
    {
        $this->logger->info('User login failed', ['user_email' => $event->credentials['email'], 'ip' => $this->request->getClientIp()]);
    }

    public function handlePasswordReset(PasswordReset $event): void
    {
        $this->logger->info('User password reset', ['user_email' => $event->user->email, 'ip' => $this->request->getClientIp()]);
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
            Registered::class => 'handleRegistered',
            Failed::class => 'handleFailed',
            PasswordReset::class => 'handlePasswordReset',
        ];
    }
}

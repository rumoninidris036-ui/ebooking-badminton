<?php

namespace App\Services;

use App\Events\Auth\UserRegistered;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        protected UserRepository $users,
    ) {
    }

    public function register(array $attributes, bool $issueToken = true, ?string $role = null): array
    {
        $user = DB::transaction(function () use ($attributes, $role) {
            $user = $this->users->create([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'password' => $attributes['password'],
                'phone' => $attributes['phone'] ?? null,
                'address' => $attributes['address'] ?? null,
                'role' => $role ?? 'user',
            ]);

            UserRegistered::dispatch($user);

            return $user;
        });

        return $this->buildAuthPayload($user, $issueToken);
    }

    public function login(array $credentials, bool $issueToken = true): array
    {
        $user = $this->attemptUser($credentials['email'], $credentials['password']);

        return $this->buildAuthPayload($user, $issueToken);
    }

    public function loginToSession(array $credentials, bool $remember = false): User
    {
        $user = $this->attemptUser($credentials['email'], $credentials['password']);

        Auth::login($user, $remember);

        return $user;
    }

    public function registerToSession(array $attributes): User
    {
        $payload = $this->register($attributes, false);

        Auth::login($payload['user']);

        return $payload['user'];
    }

    public function logoutFromSession(Request $request): void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function logoutFromToken(Request $request): void
    {
        $token = $request->user()?->currentAccessToken();

        if ($token) {
            $token->delete();
        }
    }

    public function currentUser(Request $request): ?User
    {
        return $request->user();
    }

    protected function attemptUser(string $email, string $password): User
    {
        $user = $this->users->findByEmail($email);

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user;
    }

    protected function buildAuthPayload(User $user, bool $issueToken): array
    {
        return [
            'user' => $user,
            'token' => $issueToken ? $user->createToken('mobile-app')->plainTextToken : null,
        ];
    }
}

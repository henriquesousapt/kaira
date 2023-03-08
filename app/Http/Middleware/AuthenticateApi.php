<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApi
{
    private array $pairs = [
        "(" => ")",
        "[" => "]",
        "{" => "}"
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if ($token === null) {
            return $this->unauthenticatedResponse();
        }

        if (!str_contains($token, 'Bearer ')) {
            return $this->unauthenticatedResponse();
        }

        $token = str_replace('Bearer ', '', $token);

        $stack = [];
        foreach(str_split($token) as $bracket) {
            if(!empty($stack) && $this->match(end($stack), $bracket)) {
                array_pop($stack);
            } elseif(!empty($bracket)) {
                $stack[] = $bracket;
            }
        }

        if (!empty($stack)) {
            return $this->unauthenticatedResponse();
        }

        return $next($request);
    }

    private function match($a, $b): bool
    {
        if(array_key_exists($a, $this->pairs)) {
            return $this->pairs[$a] == $b;
        } else {
            return false;
        }
    }

    public function unauthenticatedResponse(): Response
    {
        return response()->json(
            [
                'errors' => [
                    'error' => [
                        'Unauthenticated request.',
                    ],
                ],
            ],
            Response::HTTP_UNAUTHORIZED,
        );
    }
}

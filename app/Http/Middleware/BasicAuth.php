<?php

namespace App\Http\Middleware;

use App\Traits\Services\Jwt;
use Closure;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;

class BasicAuth
{
    use Jwt;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|object
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('Authorization')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $jwtToken = $request->header('Authorization');

        try {
            $details = $this->getJwtService()->decodeJwtToken($request->header('Authorization'));
            if (!$details->is_admin) {
                response()->json(['status' => false, 'message' => 'You should be logged in as admin'])->setStatusCode(422);
            }
        } catch (ExpiredException|BeforeValidException $ex) {
            return response()->json(['status' => false, 'message' => 'Invalid Token'])->setStatusCode(422);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Traits\Models\User;
use App\Traits\Services\Jwt;
use Closure;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;

class BasicAuthAdmin
{
    use Jwt;
    use User;

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
        if (!$request->hasHeader('Authorization') && !$request->has('token')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $jwtToken = $request->hasHeader('Authorization')
            ? $request->header('Authorization')
            : $request->get('token');

        try {
            $details = $this->getJwtService()->decodeJwtToken($jwtToken);
            $userDetails = $this->getUserModel()->where('email', '=', $details->email)->firstOrFail()->toArray();
            if (!$userDetails['is_admin']) {
                return response()->json(['status' => false, 'message' => 'You should be logged in as admin'])->setStatusCode(422);
            }
        } catch (ExpiredException|BeforeValidException $ex) {
            return response()->json(['status' => false, 'message' => 'Invalid Token'])->setStatusCode(422);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Traits\Services\Jwt;
use Closure;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BasicAuthAdmin
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
        if (!$request->hasHeader('Authorization') && !$request->has('token')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $jwtToken = ($request->bearerToken())
            ? $request->bearerToken()
            : ($request->hasHeader('Authorization')
                ? $request->header('Authorization')
                : $request->get('token'));

        try {
            if (!$this->getJwtService()->verifyJwtToken($jwtToken)) {
                return response()->json(['status' => false, 'message' => 'Invalid Token'])->setStatusCode(422);
            }

            $tokenDetails = $this->getJwtService()->decodeJwtToken($jwtToken);
            $this->getJwtService()->updateJwtTokenUsage($jwtToken);

            if (!$tokenDetails->is_admin) {
                return response()->json(['status' => false, 'message' => 'You should be logged in as admin'])->setStatusCode(422);
            }
        } catch (ExpiredException|BeforeValidException $ex) {
            return response()->json(['status' => false, 'message' => 'Invalid Token'])->setStatusCode(422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'User Not Found'])->setStatusCode(404);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['status' => false, 'message' => 'User Not Found'])->setStatusCode(404);
        }

        $request->merge(['user' => $tokenDetails, 'token' => $jwtToken]);

        return $next($request);
    }
}

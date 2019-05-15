<?php
namespace App\Http\Middleware;
use Closure;
use JWTAuth;
use Exception;
class jwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([ 'success' => false , 'data' => 'El token es invalido']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([ 'success' => false ,'data' => 'El token ha expirado']);
            }else{
                return response()->json([ 'success' => false ,'data' => 'Token no encontrado']);
            }
        }
        return $next($request);
    }
}
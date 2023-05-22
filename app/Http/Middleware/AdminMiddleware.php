<?php

namespace App\Http\Middleware;

use App\Constants\UserRoleConstant;
use Closure;
use App\Infastructures\Response;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminMiddleware
{
    private $response;

    public function __construct(
        Response $response)
    {
        $this->response = $response;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();
    
            if (isset($token) && $payload->get('exp') < time()) {
                return $this->response->unautorizeResponse('Token has expired');
            }

            if (auth()->guard('api')->user()->role == UserRoleConstant::Admin)
            {
                return $next($request);
            }

            return $this->response->validationResponse('You are not eligible to do this');
        }
        catch (\Exception $e)
        {
            return $this->response->validationResponse('You are not eligible to do this');
        }
    }
}

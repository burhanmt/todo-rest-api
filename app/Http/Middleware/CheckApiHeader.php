<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class CheckApiHeader
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (strtolower($request->headers->get('accept')) !== 'application/json') {
            return $this->addCorrectContentType(new Response('Wrong "accept" format!', 406));
        }

        if (
            $request->headers->has('content-type') ||
            $request->isMethod('POST') ||
            $request->isMethod('PATCH')
        ) {
            if (strtolower($request->header('content-type')) !== 'application/json') {
                return $this->addCorrectContentType(new Response('Wrong "content-type" format!', 415));
            }
        }

        return $this->addCorrectContentType($next($request));
    }

    private function addCorrectContentType(BaseResponse $response): BaseResponse
    {
        $response->headers->set('content-type', 'application/json');

        return $response;
    }
}

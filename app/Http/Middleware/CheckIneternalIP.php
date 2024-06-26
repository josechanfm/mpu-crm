<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckIneternalIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the client's IP address is an internal IP
        if($this->isIneternalIP($this->getRealClientIP($request->ip()))){
            $rip=$this->getRealClientIP($request->ip());
            
            return $next($request);
        }
        // If the IP is not inertinal, return a 403 Forbidden response
        return Inertia::render('Error',[
            'message'=>'Forbidden'
        ]);
        //return response()->json(['error'=>'Forbidden'],403);
    }

    /**
     * Check if the given IP address is an internal IP.
     * 
     * @parem string $ip
     * @return bool
     */
    protected function isIneternalIP($ip){
        $realIP=$this->getRealClientIP($ip);

        $privateIPBlocks=[
            '127.0.0.1',
            '172.16.',
            '172.13.',
            '172.14.',
            '172.15.',
            '172.16.',
            '172.17.',
            '172.18.',
            '172.19.',
            '172.20.',
            '172.21.',
            '172.22.',
            '172.23.',
            '172.24.',
            '172.25.',
            '172.26.',
            '172.27.',
            '172.28.',
            '172.29.',
            '172.30.',
            '172.31.',
            '202.175.6',
            '202.175.8',
            '202.175.9',
            '202.175.24',
            '202.175.25',
            // '172.16.0.0/12',
            // '202.175.6.0/24',
            // '202.175.8.0/24',
            // '202.175.9.0/24',
            // '202.175.24.0/24',
            // '202.175.25.0/24',
        ];
        //dd($realIP);
        foreach($privateIPBlocks as $block){
            if(strpos($realIP, $block) === 0){
                return true;
            }
        }
        return false;
    }

    protected function getRealClientIP($ip){
        $realIP=$ip;
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']){
            $http_x_headers=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
            $realIP= $http_x_headers[0];
        }
        return $realIP;
    }
}

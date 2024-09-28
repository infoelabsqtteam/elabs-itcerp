<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class CheckMaintenanceMode
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
        if(!empty(DB::table('settings')->where('settings.setting_key', '=', 'UNDER_MAINTENANCE')->pluck('settings.setting_value')->first())){
            return response()->view('errors.maintenanceMode');
        }else{
            return $next($request);
        }   
    }
}

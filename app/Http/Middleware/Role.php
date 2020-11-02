<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param array $roles
     * @return mixed
     * check if role of current user matches action with granted premission
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        foreach($roles as $role) {

            if($user->checkRole($role))
                return $next($request);
        }
        Alert::error('Not Authorized', 'You are not allowed to go there at this moment')->autoClose(2000);


        return redirect('/home');
    }
}

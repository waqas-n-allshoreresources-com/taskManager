<?php

namespace App\Http\Middleware;

use Closure;

class MyAuthMiddleware
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
	$user = $request->session()->get('user');
        if (isset($user->role_slug) && $user->role_slug != "task.moderator")
        {
	    $request->session()->forget('user');
	    $messageBag = new \Illuminate\Support\MessageBag;
	    $messageBag->add('auth-validation', 'Sorry! you are not authorized to use this application.');

	    return redirect('/auth/login')->withErrors($messageBag->all());
        }
	else
	{
        	return $next($request);
	}
    }
}

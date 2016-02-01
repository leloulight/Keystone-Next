<?php namespace App\Http\Middleware;

use Closure;

class Setup {

	public function handle($request, Closure $next)
	{
		if ($request->user()->is_queued) {
			return redirect('/setup');
		}

		return $next($request);
	}

	

}

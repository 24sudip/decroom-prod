<?php
namespace App\Http\Middleware;

use App\Permission;
use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AuthGatesMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user) {
            $permissions = Permission::all();

            foreach ($permissions as $key => $permission) {
                Gate::define($permission->permission_slug, function (User $user) use ($permission) {
                    return $user->hasPermission($permission->permission_slug);
                });
            }
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetPermissionsTeamId
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            $currentTeam = getPermissionsTeamId();

            setPermissionsTeamId(null);
            $isSuperAdmin = $user->hasRole('super_admin');

            setPermissionsTeamId($currentTeam);

            if (!$isSuperAdmin) {
                if ($teamId = session('current_branch_id')) {
                    setPermissionsTeamId($teamId);
                    $user->unsetRelation('roles');
                    $user->unsetRelation('permissions');
                }
            }
        }

        return $next($request);
    }
}

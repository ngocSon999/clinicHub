<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $branches = collect();
            $currentBranchId = null;

            if (Auth::check()) {
                /** @var User $user */
                $user = Auth::user();

                if (!$user->hasRole('super_admin')) {
                    $branches = $user->allRoles
                        ->loadMissing('team')
                        ->pluck('team')
                        ->filter()
                        ->unique('id')
                        ->values();

                    $currentBranchId = session('current_branch_id');

                    if (!$currentBranchId) {
                        $currentBranchId = $user->last_branch_id;

                        if (!$currentBranchId || !$branches->contains('id', $currentBranchId)) {
                            $currentBranchId = $branches->first()?->id;
                            if ($currentBranchId) {
                                $user->forceFill([
                                    'last_branch_id' => $currentBranchId,
                                ])->save();
                            }
                        }
                        session(['current_branch_id' => $currentBranchId]);
                    }
                }
            }

            $view->with([
                'userBranches' => $branches,
                'currentBranchId' => $currentBranchId,
            ]);
        });
    }
}

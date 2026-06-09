<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request): Factory|View|RedirectResponse
    {
        return view('dashboard');
    }

    public function setLocale($locale): RedirectResponse
    {
        if (! in_array($locale, ['en', 'vi'])) {
            abort(400);
        }

        App::setLocale($locale);
        session(['locale' => $locale]);

        return back();
    }
}

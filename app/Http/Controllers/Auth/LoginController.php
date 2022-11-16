<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

// use App\Providers\RouteServiceProvider;
// use Illuminate\Support\Facades\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Куда выполнять редирект после входа в систему и после выхода из системы
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected string $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Сразу после входа выполняем редирект и устанавливаем flash-сообщение
     */
    protected function authenticated(Request $request, $user): Redirector|Application|RedirectResponse
    {
        return redirect($this->redirectTo)
            ->with('status', __('You are logged in!'));
    }

    /**
     * Сразу после выхода выполняем редирект и устанавливаем flash-сообщение
     */
    protected function loggedOut(Request $request): Redirector|Application|RedirectResponse
    {
        return redirect($this->redirectTo)
            ->with('status', trans('auth.loggedout'));
    }
}

<?php

namespace App\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\TemplateEngine\Contracts\TemplatesEngineContract;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly TemplatesEngineContract $templatesEngine,
        private readonly Redirector              $redirector,
    ) {
    }

    public function loginForm(): View
    {
        return $this->templatesEngine->getView('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $user = Auth::attempt($credentials);

        if ($user === null) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $request->session()->regenerate();
        return $this->redirector->intended();
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        return $this->redirector->to('/');
    }
}

<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Shop\Employee\Requests\LoginRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @return Factory|RedirectResponse|View
     */
    public function showLoginForm()
    {
        if (auth()->guard('employee')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.admin.login');
    }

    /**
     * @param LoginRequest $request
     * @return Response|\Symfony\Component\HttpFoundation\Response|void
     * @throws ValidationException
     */
    public function login(LoginRequest $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $details = $request->only('email', 'password');
        $details['status'] = 1;
        if (auth()->guard('employee')->attempt($details)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}

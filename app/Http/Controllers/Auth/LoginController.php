<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Hasob\FoundationCore\Models\User;
use Auth;

use Hasob\FoundationCore\Models\Setting;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Managers\OrganizationManager;

class LoginController extends Controller
{


    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $org = \FoundationCore::current_organization();
        if ($org != null){
            $credentials = $request->only('email', 'password');
 
            if (Auth::attempt([
                'email'=>$request->email, 
                'password'=>$request->password, 
                'is_disabled'=>false, 
                'organization_id'=>$org->id])
                ) {
                return redirect()->intended('dashboard');
            }
        }
        
    }


    protected function authenticated(Request $request, $user)
    {
        $org = \FoundationCore::current_organization();
        if ($org != null && $user->organization_id!=$org->id){
            Auth::logout();
            return redirect(route('login'))->withErrors(['disabled' => trans('disabled')]);
        }

        if ($user->is_disabled == 1) {
            Auth::logout();
            return redirect(route('login'))->withErrors(['disabled' => trans('disabled')]);

        }else{
            return redirect()->intended('dashboard');
        }
    }
    
}

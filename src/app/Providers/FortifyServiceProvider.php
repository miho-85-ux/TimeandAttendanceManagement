<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\LoginResponse ;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                $role = session('role');

                session()->forget('role');

                if($role === 'admin'){
                    return redirect('/admin/login');
                }

                return redirect('/login');
            }
        });

        $this->app->instance(LoginResponse ::class, new class implements LoginResponse  {
            public function toResponse($request)
            {                
                $user = auth()->user();
                
                if(!$user){
                    return redirect('/login');
                }

                session(['role' => $user->role]);

                if($user->role === 'admin'){
                    return redirect('/admin/attendance/list');
                }

                return redirect('/attendance/list');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        app()->bind(FortifyLoginRequest::class, LoginRequest::class);

        $this -> app -> singleton(RegisterResponse::class, function(){
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    return redirect('/email/verify');

                }
            };
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

        Fortify::verifyEmailView(function(){
            
            return view('auth.verify-email');
        });

        
    }
}

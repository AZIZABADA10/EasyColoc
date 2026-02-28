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
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);
 
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements \Laravel\Fortify\Contracts\LoginResponse {
                public function toResponse($request)
                {
                    if (session()->has('invitation_token')) {
                        return redirect()->route(
                            'invitations.accept',
                            ['token' => session('invitation_token')]
                        );
                    }

                    return redirect()->route('dashboard');
                }
            };
        });

        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements \Laravel\Fortify\Contracts\RegisterResponse {
                public function toResponse($request)
                {
                    if (session()->has('invitation_token')) {
                        return redirect()->route(
                            'invitations.accept',
                            ['token' => session('invitation_token')]
                        );
                    }

                    return redirect()->route('dashboard');
                }
            };
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
            );

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->session()->get('login.id')
            );
        });
    }
}
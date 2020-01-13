<?php

namespace App\Providers;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 注册发出访问令牌并撤销访问令牌、客户端和个人访问令牌所必需的路由
        Passport::routes();

        // Passport Token 过期时间
        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        // Passport Refresh Token 过期时间
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}

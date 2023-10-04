<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            // $link = $user->hasRole('Estudante') ? env('RESET_PASSWORD_URL') : env('APP_URL');
            // http://localhost:8000/reset-password/8f080cd80cccbdb4202a13442cc7f0d733f5515df6a5c689f178aa15bec5efea?email=admin%40proculdiscipulus.com;
            $urlAdmin = env("APP_URL").'/reset-password/'.$token.'?email='.$user->email;
            $urlStudent = env("RESET_PASSWORD_URL").'/reset_password?token='.$token.'&email='.$user->email;
            return $user->hasRole('Estudante') ? $urlStudent : $urlAdmin;
        });
        
        $this->registerPolicies();

        $permissions = Permission::with('roles')->get();

        foreach ($permissions as $permission) {
            Gate::define($permission->name, function(User $user) use ($permission){
                foreach ($user->roles as $role) {
                    if ($role->permissions->contains('name',$permission->name)) {
                        return true;
                    }
                }
                return false;
            });
        }
    }
}

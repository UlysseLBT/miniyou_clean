<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Media::class => \App\Policies\MediaPolicy::class,
        \App\Models\Post::class  => \App\Policies\PostPolicy::class,
        \App\Models\Community::class => \App\Policies\CommunityPolicy::class
    ];

    public function boot(): void
    {
        // en L11, c’est safe de faire directement :
        $this->registerPolicies();
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event; 
use Illuminate\Auth\Events\Verified;  
use App\Listeners\SendWelcomeEmail;
use Illuminate\Support\Facades\Gate; // <--- Pastikan import Gate
use App\Models\Article;
use App\Models\Ebook;
use App\Policies\ArticlePolicy;
use App\Policies\EbookPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            Verified::class,
            SendWelcomeEmail::class,
        );

        Gate::policy(Article::class, ArticlePolicy::class);
        Gate::policy(Ebook::class, EbookPolicy::class);
    }
}

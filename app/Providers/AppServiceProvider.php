<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //if($this->app->environment('production')) {
        if(env('production')){
            URL::forceScheme('https');
        }
        Builder::macro('whereLike', function($columns, $search) {
            $this->where(function($query) use ($columns, $search) {
              foreach(\Arr::wrap($columns) as $column) {
                $query->orWhere($column,'LIKE', '%'.$search.'%');
              }
            });
           
            return $this;
          });
    }
}

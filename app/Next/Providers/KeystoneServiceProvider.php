<?php

	namespace App\Next\Providers;

	use App\Next\Auth\KeystoneUserProvider;
	use Illuminate\Support\ServiceProvider;

	class KeystoneServiceProvider extends ServiceProvider {

	    /**
	     * Bootstrap the application services.
	     *
	     * @return void
	     */
	    public function boot()
	    {
	        $this->app['auth']->extend('keystone',function()
	        {
	            return new KeystoneUserProvider();
	        });
	    }

	    /**
	     * Register the application services.
	     *
	     * @return void
	     */
	    public function register()
	    {
	        //
	    }

	}

?>
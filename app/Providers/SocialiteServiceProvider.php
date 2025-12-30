<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Provider as SocialiteProvider;
use Laravel\Socialite\Facades\Socialite;

class SocialiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Disable SSL verification for development environment
        if (config('app.env') === 'local' && env('CURL_VERIFY_SSL', true) === false) {
            // Create a Guzzle client with SSL verification disabled
            $httpClient = new Client([
                'verify' => false,
                'timeout' => 10,
            ]);

            // Override Socialite's HTTP client factory
            $this->overrideSocialiteHttpClient($httpClient);
        }
    }

    /**
     * Override Socialite HTTP client
     */
    private function overrideSocialiteHttpClient(Client $httpClient)
    {
        // This is a workaround - we'll set it via manager
        $manager = Socialite::getManager();
        
        // Access protected config to modify guzzle options
        if (method_exists($manager, 'getConfig')) {
            $config = $manager->getConfig();
            if (isset($config['guzzle'])) {
                $config['guzzle']['verify'] = false;
            }
        }
    }
}

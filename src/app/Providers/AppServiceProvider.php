<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Elastic\Elasticsearch\Client;
use ClickHouseDB\Client as ClickHouseClient;
use Elastic\Elasticsearch\ClientBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $hosts = [
                env('ELASTICSEARCH_HOST', 'localhost:9200'),
            ];

            return ClientBuilder::create()
                ->setHosts($hosts)
                ->build();
        });

        $this->app->singleton(ClickHouseClient::class, function ($app) {
            $config = [
                'host'     => env('CLICKHOUSE_HOST', 'localhost'),
                'port'     => env('CLICKHOUSE_PORT', 9000),
                'username' => env('CLICKHOUSE_USERNAME', 'default'),
                'password' => env('CLICKHOUSE_PASSWORD', 'mysecret'),
                'database' => env('CLICKHOUSE_DATABASE', 'default'),
            ];

            return new ClickHouseClient($config);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

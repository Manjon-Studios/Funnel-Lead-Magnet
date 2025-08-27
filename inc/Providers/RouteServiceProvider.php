<?php

namespace Providers;

final class RouteServiceProvider {
    public function __construct(
        private AppServiceProvider $app
    ) {}

    public function boot(): void {
        add_action('rest_api_init', function () {
            $this->app->makeOptinRoutes()->register();
        });
    }
}
<?php

namespace Providers;

use Application\UseCases\OptInLead;
use Domain\Ports\DisposableEmailService;
use Domain\Ports\LeadPublisher;
use Domain\Ports\LeadRepository;
use Infrastructure\Http\Controllers\OptinController;
use Infrastructure\Http\Routes\OptinRoutes;
use Infrastructure\Persistence\WpdbLeadRepository;
use Infrastructure\Services\DisposableDomainListService;
use Infrastructure\Events\WordPressLeadPublisher;
use wpdb;


final class AppServiceProvider {
    public function __construct(
        private wpdb $db,
        private string $basePath
    ) {}

    public function makeOptinRoutes(): OptinRoutes {
        $repo   = $this->makeLeadRepository();
        $disp   = $this->makeDisposableService();
        $pub    = $this->makeLeadPublisher();
        $use    = new OptInLead($repo, $disp, $pub);
        $ctrl   = new OptinController($use);
        return new OptinRoutes($ctrl);
    }

    private function makeLeadRepository(): LeadRepository {
        return new WpdbLeadRepository($this->db);
    }

    private function makeDisposableService(): DisposableEmailService {
        $list = $this->basePath . '/Funnel/data/disposable.txt';
        return new DisposableDomainListService($list);
    }

    private function makeLeadPublisher(): LeadPublisher {
        return new WordPressLeadPublisher();
    }
}
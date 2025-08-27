<?php

namespace Infrastructure\Services;

use Domain\Ports\DisposableEmailService;

final class DisposableDomainListService implements DisposableEmailService {
    public function __construct(private string $listPath) {}

    public function isDisposable(string $emailDomain): bool {
        $list = get_transient('flm_disposable_domains');
        if ($list === false) {
            $list = [];
            if (file_exists($this->listPath)) {
                $lines = file($this->listPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
                $list  = array_map(fn($s) => strtolower(trim($s)), $lines);
            }
            set_transient('flm_disposable_domains', $list, 12 * HOUR_IN_SECONDS);
        }
        $d = strtolower($emailDomain);
        foreach ($list as $suf) {
            if ($suf !== '' && strlen($suf) <= strlen($d)
                && substr_compare($d, $suf, -strlen($suf), null, true) === 0) {
                return true;
            }
        }
        return false;
    }
}
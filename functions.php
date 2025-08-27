<?php
use Providers\AppServiceProvider;
use Providers\RouteServiceProvider;

if (!defined('ABSPATH')) exit;

define('FLM_VITE_DEV', true);
define('FLM_INC_PATH', get_template_directory() . '/inc');
define('FLM_VENDOR_AUTOLOAD', get_template_directory() . '/vendor/autoload.php');

// Core y Blocks
require_once get_template_directory() . '/inc/Core/enqueue_assets.php';
require_once get_template_directory() . '/blocks.php';

/**
 * Autoload: Composer si existe; si no, PSR-4 para tus namespaces "planos"
 * Soporta: Providers\, Infrastructure\, Application\, Domain\, y también FLM\ (por si luego lo usas)
 */
if (file_exists(FLM_VENDOR_AUTOLOAD)) {
    require FLM_VENDOR_AUTOLOAD;
} else {
    spl_autoload_register(function ($class) {
        $maps = [
            'Providers\\'      => FLM_INC_PATH . '/Providers/',
            'Infrastructure\\' => FLM_INC_PATH . '/Infrastructure/',
            'Application\\'    => FLM_INC_PATH . '/Application/',
            'Domain\\'         => FLM_INC_PATH . '/Domain/',
            'FLM\\'            => FLM_INC_PATH . '/'
        ];

        foreach ($maps as $prefix => $baseDir) {
            $len = strlen($prefix);
            if (strncmp($class, $prefix, $len) !== 0) continue;
            $relative = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) { require $file; return; }
        }
    });
}

// Bootstrap: ahora estas clases existen bajo Providers\
add_action('after_setup_theme', function () {
    global $wpdb;
    $base = rtrim(FLM_INC_PATH, '/');

    $app  = new AppServiceProvider($wpdb, $base);
    (new RouteServiceProvider($app))->boot();
});

// Migración al activar/cambiar tema
add_action('after_switch_theme', function () {
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $table   = $wpdb->prefix . 'flm_leads';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL,
        first_name VARCHAR(191) NULL,
        status VARCHAR(32) NOT NULL,
        ip_hash CHAR(64) NULL,
        user_agent TEXT NULL,
        consent_at DATETIME NOT NULL,
        source VARCHAR(191) NULL,
        PRIMARY KEY (id),
        UNIQUE KEY email_unique (email)
    ) $charset;";
    dbDelta($sql);
});

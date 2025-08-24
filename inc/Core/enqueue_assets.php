<?php
add_action('wp_enqueue_scripts', function() {

    $origin = 'http://localhost:5173';

    if ( defined('FLM_VITE_DEV') && FLM_VITE_DEV )
    {
        wp_enqueue_script('vite-client', $origin . '/@vite/client', [], null, false);
        wp_enqueue_script('flm-app',    $origin . '/assets/src/js/app.js', [], null, true);


    } else
    {
        flm_enqueue_entries(['app','style']);
    }
});

add_filter('script_loader_tag', function ($tag, $handle, $src) {
    if (in_array($handle, ['vite-client', 'flm-app'], true)) {
        return '<script type="module" crossorigin="anonymous" src="' . esc_url($src) . '"></script>';
    }
    return $tag;
}, 10, 3);
<?php


function flm_enqueue_entries( array $entries )
{
    $dist_dir = get_template_directory() . '/assets/dist';
    $dist_uri = get_template_directory_uri() . '/assets/dist';
    $manifest_path = $dist_dir . '/manifest.json';
    if ( ! file_exists( $manifest_path ) ) return;

    static $manifest = null;
    if ( $manifest === null ) {
        $manifest = json_decode( file_get_contents( $manifest_path ), true );
    }

    foreach ( $entries as $entry ) {

        $js_key = "assets/src/js/{$entry}.js";
        if ( isset( $manifest[ $js_key ] ) ) {
            $item = $manifest[ $js_key ];
            if ( ! empty( $item['file'] ) ) {
                wp_enqueue_script( "vt-{$entry}", "{$dist_uri}/{$item['file']}", [], null, true );
            }
            if ( ! empty( $item['css'] ) ) {
                foreach ( $item['css'] as $css_file ) {
                    wp_enqueue_style( "vt-{$entry}", "{$dist_uri}/{$css_file}", [], null );
                }
            }
            continue;
        }

        $css_key_variants = [
            "{$entry}.css",
            "assets/src/css/{$entry}.css",
        ];
        foreach ( $css_key_variants as $css_key ) {
            if ( isset( $manifest[ $css_key ] ) ) {
                $item = $manifest[ $css_key ];
                if ( ! empty( $item['file'] ) ) {
                    wp_enqueue_style( "vt-{$entry}", "{$dist_uri}/{$item['file']}", [], null );
                }
                continue 2;
            }
        }

        if ( WP_DEBUG ) error_log( "Vite: entry '{$entry}' no encontrada en manifest" );
    }
}
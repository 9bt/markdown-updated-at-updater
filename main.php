<?php

require_once __DIR__ . '/bin/updated-at-handler.php';

if ( ! isset( $argv[1] ) ) {
    throw new Exception();
}

$base_target_dir = $argv[1];
$condition       = isset( $argv[2] ) ? $argv[2] : 'now';

$target_paths = [];
foreach ( glob( __DIR__ . '/resources/*.csv' ) as $resource_path ) {
    $file = new SplFileObject( $resource_path );
    $file->setFlags( SplFileObject::READ_CSV );

    foreach ( $file as $row ) {
        $target_path = $row[0];
        $target_path = $base_target_dir . preg_replace( '/\.html$/', '.md', $target_path );
        if ( ! file_exists( $target_path ) ) {
            trigger_error( sprintf( '%s does not exist', $target_path ) , E_USER_WARNING );
            continue;
        }

        $content = file_get_contents( $target_path );
        $content = UpdatedAtHandler::update_updated_at( $content, $condition );

        file_put_contents( $target_path, $content );
    }
}

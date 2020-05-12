<?php

require_once __DIR__ . '/bin/updated-at-handler.php';

if ( ! isset( $argv[1] ) ) {
    throw new Exception();
}

$base_target_dir = $argv[1];
$condition       = isset( $argv[2] ) ? $argv[2] : 'now';

$target_file = new SplFileObject( __DIR__ . '/resources/target.csv' );
$target_file->setFlags( SplFileObject::READ_CSV );

$excluding_path_file = new SplFileObject( __DIR__ . '/resources/exclude.csv' );
$excluding_path_file->setFlags( SplFileObject::READ_CSV );

$excluding_paths = [];
foreach( $excluding_path_file as $row ) {
    $excluding_paths[] = trim( $row[0] );
}

foreach ( $target_file as $row ) {
    $target_path = trim( $row[0] );
    if ( '' === $target_path || in_array( $target_path, $excluding_paths ) ) {
        continue;
    }

    $target_path = $base_target_dir . preg_replace( '/\.html$/', '.md', $target_path );
    if ( ! file_exists( $target_path ) ) {
        trigger_error( sprintf( '%s does not exist', $target_path ) , E_USER_WARNING );
        continue;
    }

    $content = file_get_contents( $target_path );
    $content = UpdatedAtHandler::update_updated_at( $content, $condition );

    file_put_contents( $target_path, $content );
}

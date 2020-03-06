<?php

require_once __DIR__ . '/vendor/autoload.php';

use Spatie\YamlFrontMatter\YamlFrontMatter;

class UpdatedAtHandler
{
    private static $reg_exp = '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}/';

    public static function update_updated_at( $content, $condition ) {
        $front_matter = YamlFrontMatter::parse( $content );
        $updated_at   = self::convert_date_for_condition( $front_matter->updated_at, $condition );

        return preg_replace( self::$reg_exp, $updated_at->format('Y-m-d H:i'), $content );
    }

    private static function convert_date_for_condition( $date, $condition ) {
        if ( '' === $condition || 'now' === $condition ) {
            return new DateTime( 'Asia/Tokyo' );
        }

        $date = new DateTime( $date );
        $date->modify( $condition );

        return $date;
    }
}

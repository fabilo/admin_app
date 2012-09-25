<?php
spl_autoload_register( 'Autoload_Register::register' );
 
class Autoload_Register {
 
	const CI_PREFIX     = "CI_";
	const MY_PREFIX			= "MY_";

    public static function register( $insClass ) {
        $sPrefix = substr( $insClass, 0, 3 );

        if ( $sPrefix == self::CI_PREFIX || $sPrefix == self::MY_PREFIX) {
            return;
        }
				require_once $insClass.EXT;
    }
}
<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ){
   exit;
}

if ( get_option( 'blondcoin_donation_options' ) != false ){
   delete_option( 'blondcoin_donation_options' );
}

?>

<?php
/*
  Plugin Name: WP Security Audit Log for Paid Memberships Pro
  Plugin URI: https://github.com/bwsolutions/wpsal4pmpro
  Description: an addon to WP Security Audit Log Plugin to track events in Paid Memberships Pro
  Version: 1.1
  Author: Bill Stoltz
  Author URI:
  Depends: WP Security Audit Log, Paid Memberships Pro
  License: GPL3
  */

register_activation_hook( __FILE__, 'wsalpmpro_plugin_activation' );

function wsalpmpro_plugin_activation() {
	global $wp_version;
	$min_wsal_version  = '2.6.2';
	$wsal_plugin_dir   = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'wp-security-audit-log/wp-security-audit-log.php';
	$min_pmpro_version = '1.8.13';
	$pmpro_plugin_dir  = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'paid-memberships-pro/paid-memberships-pro.php';

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$pmproData = get_plugin_data( $pmpro_plugin_dir, false, true );
	if ( isset( $pmproData['Version'] ) && ( version_compare( $pmproData['Version'], $min_pmpro_version, '<' ) ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die(
			'<p>' .
			sprintf(
				'This plugin can not be activated because it requires at least version %s of Paid Memberships Pro. Please upgrade Paid Memberships Pro and then re-activate this plugin.',
				$min_pmpro_version
			)
			. '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . 'go back' . '</a>'
		);
	}

	$wsalData = get_plugin_data( $wsal_plugin_dir, false, true );
	if ( isset( $wsalData['Version'] ) && ( version_compare( $wsalData['Version'], $min_wsal_version, '<' ) ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die(
			'<p>' .
			sprintf(
				'This plugin can not be activated because it requires at least version %s of WP Security Audit Log. Please upgrade WP Security Audit Log and then re-activate this plugin.',
				$min_wsal_version
			)
			. '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . 'go back' . '</a>'
		);
	}
}

function wsalpmpro_wsal_init ($wsal) {
    include_once 'pmpro-alerts.php';
    $wsal->alerts->RegisterGroup($pmpro_alerts);

    $sensorDir = 'Sensors' . DIRECTORY_SEPARATOR ;
    if (is_dir($sensorDir) && is_readable($sensorDir)) {
        foreach (glob($sensorDir . '*.php') as $file) {
            require_once($file);
            $file = substr($file, 0, -4);
            $class = "WSAL_Sensors_" . str_replace($sensorDir, '', $file);
            $this->AddFromClass($class);
        }
    }
}
add_action('wsal_init', 'wsalpmpro_wsal_init');


<?php
/*
  Plugin Name: WP Security Audit Log for Paid Memberships Pro
  Plugin URI: https://github.com/bwsolutions/wpsal4pmpro
  Description: an addon to the WP Security Audit Log Plugin to track events in the Paid Memberships Pro plugin
  Version: 1.1.5
  Author: Bill Stoltz
  Author URI:
  Depends: WP Security Audit Log, Paid Memberships Pro
  License: GPL3
  */
define('WSALPMPRO_VERSION', '1.1.5');

define('PMPRO_PLUGIN_NAME', 'paid-memberships-pro/paid-memberships-pro.php');
define('WSAL_PLUGIN_NAME', 'wp-security-audit-log/wp-security-audit-log.php');

register_activation_hook( __FILE__, 'wsalpmpro_plugin_activation' );


function wsalpmpro_plugin_activation() {
	$dir = plugin_dir_path(__DIR__);

	$min_wsal_version  = '2.6.2';
	$wsal_plugin_dir   = $dir . WSAL_PLUGIN_NAME ;

	$min_pmpro_version = '1.8.13';
	$pmpro_plugin_dir  = $dir . PMPRO_PLUGIN_NAME;

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}


	if (is_plugin_active(PMPRO_PLUGIN_NAME)) {
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
	} else {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die(
			'<p>' .
			sprintf(
				'This plugin can not be activated because it Paid Memberships Pro is not active. Please activate Paid Memberships Pro and then re-activate this plugin.',
				$min_pmpro_version
			)
			. '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . 'go back' . '</a>'
		);
	}

	if (is_plugin_active(WSAL_PLUGIN_NAME)) {
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
	} else {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die(
			'<p>' .
			sprintf(
				'This plugin can not be activated because WP Security Audit Log is not active. Please activate WP Security Audit Log and then re-activate this plugin.',
				$min_wsal_version
			)
			. '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . 'go back' . '</a>'
		);
	}
}

function wsalpmpro_wsal_init ($wsal) {

		include_once 'pmpro-alerts.php';

		/** @var array $pmpro_alerts  // defined in pmpro-alerts.php */
		$wsal->alerts->RegisterGroup( $pmpro_alerts );

		$sensorDir     = trailingslashit( dirname( __FILE__ ) );
		$sensorDirPath = $sensorDir . 'Sensors' . DIRECTORY_SEPARATOR;

		if ( is_dir( $sensorDirPath ) && is_readable( $sensorDirPath ) ) {
			foreach ( glob( $sensorDirPath . '*.php' ) as $file ) {
				require_once( $file );
				$file  = substr( $file, 0, - 4 );
				$class = "WSAL_Sensors_" . str_replace( $sensorDirPath, '', $file );
				$wsal->sensors->AddFromClass( $class );
			}
		}
}
add_action('wsal_init', 'wsalpmpro_wsal_init');


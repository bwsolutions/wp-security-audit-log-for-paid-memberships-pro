<?php
/*
  Plugin Name: WP Security Audit Log for Paid Memberships Pro
  Plugin URI: https://github.com/bwsolutions/wpsal4pmpro
  Description: an addon to WP Security Audit Log Plugin to track events in Paid Memberships Pro
  Version: 1.0
  Author: Bill Stoltz
  Author URI:
  License: GPL3
  */

// If WP Security Audit Log not installed - abort.

if ( !class_exists( 'WpSecurityAuditLog' ) ) exit();

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


<?php
/**
 * Drift ADM
 * 
 * @package DriftADM
 * @author Willon Nava
 * @copyright 2018 Willon Nava
 * @license GPLv2 or later
 * 
 * @wordpress-plugin
 * Plugin Name:  Drift ADM
 * Description:  Drift ADM entirely customizes Wordpress dashboard with a modern design and options.
 * Version:      0.1
 * Author:       Willon Nava
 */
defined( 'ABSPATH' ) || die;

/**
 * Constants
 */
define( 'DRIFTADM_ROOT_PATH', plugin_dir_url( __FILE__ ) );

/**
 * Includes
 */
include_once( 'classes/DriftADM_Loader.php' );
new DriftADM_Loader;

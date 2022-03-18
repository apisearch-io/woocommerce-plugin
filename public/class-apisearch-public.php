<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://apisearch.io
 * @since      1.0.0
 *
 * @package    Apisearch
 * @subpackage Apisearch/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Apisearch
 * @subpackage Apisearch/public
 * @author     Apisearch Team <info@apisearch.io>
 */
class Apisearch_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    public function insertWidget()
    {
        $endpoint = get_option('apisearch_admin_endpoint');
        $adminEndpoint = get_option('apisearch_admin_endpoint');
        $indexId = get_option('apisearch_index_id');

        echo <<<END_SCRIPT
<link href="$endpoint" rel="dns-prefetch" crossorigin="anonymous">

<script
    type="application/javascript"
    src='$adminEndpoint/$indexId.iframe.min.js'
    charset='UTF-8'
    crossorigin="anonymous"
></script>
END_SCRIPT;
    }
}

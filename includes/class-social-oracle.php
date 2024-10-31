<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Social_Oracle
 * @subpackage Social_Oracle/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Social_Oracle
 * @subpackage Social_Oracle/includes
 * @author     Jaan Koppe <jaankdesign@gmail.com>
 */
class Social_Oracle {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Social_Oracle_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	protected $host;

	public function __construct() {
		if ( defined( 'SOCIAL_ORACLE_VERSION' ) ) {
			$this->version = SOCIAL_ORACLE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'social-oracle';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->host = 'https://www.smp.socialoracle.app';
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Social_Oracle_Loader. Orchestrates the hooks of the plugin.
	 * - Social_Oracle_i18n. Defines internationalization functionality.
	 * - Social_Oracle_Admin. Defines all hooks for the admin area.
	 * - Social_Oracle_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-social-oracle-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-social-oracle-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-social-oracle-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-social-oracle-public.php';

		$this->loader = new Social_Oracle_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Social_Oracle_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Social_Oracle_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Social_Oracle_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'init' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'notice_html' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Social_Oracle_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_head', $this, 'add_snippet' );
		$this->loader->add_action( 'woocommerce_checkout_order_processed', $this, 'send_order_data' );
		$this->loader->add_action( 'add_option_socialoracle_api_key', $this, 'send_latest_orders', 999, 0);
		$this->loader->add_action( 'update_option_socialoracle_api_key', $this, 'send_latest_orders', 999, 0);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_site_host_url() {
		$input = get_site_url();

		// in case scheme relative URI is passed, e.g., //www.google.com/
		$input = trim($input, '/');

		// If scheme not included, prepend it
		if (!preg_match('#^http(s)?://#', $input)) {
			$input = 'http://' . $input;
		}

		$urlParts = parse_url($input);

		// remove www
		$domain = preg_replace('/^www\./', '', $urlParts['host']);
		if( $urlParts['port'] ) {
			$domain = $domain . ':' . $urlParts['port'];
		}
		return $domain;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Social_Oracle_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function get_host() {
		return $this->host;
	}

	public function is_valid_api_key( $key ) {
		if( isset( $key ) && (strlen($key) == 69 || strlen($key) == 68) ) {
			return true;
		}
		return false;
	}

	public function get_api_key() {
		$key = get_option('socialoracle_api_key');
		if( $this->is_valid_api_key($key) ) {
			return $key;
		}
		return null;
	}

	public function get_public_api_key() {
		$key = get_option('socialoracle_public_api_key');
		if( $this->is_valid_api_key($key) ) {
			return $key;
		}
		return null;
	}

	public function add_snippet() {
		$so_public_api_key = $this->get_public_api_key();
		?>

		<script async src="https://cdn.socialoracle.app/smp.js?key=<?php echo $so_public_api_key; ?>"></script>

		<?php
	}

	public function send_request($path, $data, $ignoreAuth = false) {
		try {
			$headers = array(
				'Content-Type' => 'application/json'
			);

			$apiKey = $this->get_api_key();
			
			$url = $this->get_host() . $path;
			$data = array(
				'headers' => $headers,
				'body' => json_encode($data),
			);

			$response = wp_remote_post($url, $data);
			return $response;
		}catch(Exception $err) {
			$this->handle_error('Failed to send request', $err, $data);
		}
	}

	public function send_order_data($id) {
		
		try {
			$order = wc_get_order($id);
			$data = $this->get_order_payload($order);
			return $this->send_request('/api/campaigns/woocommerce/order', $data);
		} catch(Exception $err) {
			$this->handle_error('Failed to send webhook', $err, $order);
		}
	}

	public function send_latest_orders() {
		try {

			$api_key = $this->get_api_key();
			if($api_key == null) {
				$this->log('Bad API key update');
				return;
			}
			$final_orders = [];
			$latestOrders = wc_get_orders(array(
				'limit' => 20,
				'type' => 'shop_order',
				'orderby' => 'date',
				'order' => 'DESC'
			));

			foreach($latestOrders as $latestOrder) {
                array_push($final_orders, $this->get_order_payload($latestOrder));
			}

			$data = array(
				'apiKey' => $this->get_api_key(),
				'integrationID' => 1,
				'storeDomain' => $this->get_site_host_url(),
				'orders' => $final_orders,
				'setup' => true, // set this to false, when not changing api key.
			);

			$response = $this->send_request('/api/campaigns/woocommerce/order/bulk', $data);
			$response_body = wp_remote_retrieve_body($response);
			$resData = ( !is_wp_error( $response_body ) ) ? json_decode( $response_body, true ) : null;

			
			if( isset($resData['records']) ) {
				$this->log($resData['records']['publicKey']);
				update_option('socialoracle_public_api_key', $resData['records']['publicKey'], true);
			}else{
				$this->log('records not set');
			}

			return $response;
		}catch(Exception $err) {
			$this->handle_error('Failed to send latest orders data', $err);
		}
	}

	public function get_order_payload($order) {
		$payload = array(
			'apiKey' => $this->get_api_key(),
			'integrationID' => 1,
			'storeDomain' => $this->get_site_host_url(),
			'orderId' => $order->get_id(),
			'firstName' => $order->get_billing_first_name(),
			'lastName' => $order->get_billing_last_name(),
			'email' => $order->get_billing_email(),
			'ip' => $order->get_customer_ip_address(),
			'ips' => $this->get_ips(),
			'siteUrl' => get_site_url(),
			'total' => (int) $order->get_total(),
			'currency' => $order->get_currency(),
			'products' => $this->get_products_array($order),
		);
		$payload['name'] = $payload['firstName'] . ' ' . $payload['lastName'];

		if(method_exists($order, 'get_date_created')) {
			$date = $order->get_date_created();
			if(!empty($date) && method_exists($date, '__toString')) {
				$payload['purchaseTime'] = $order->get_date_created()->__toString();
			}
		}        
		return $payload;
	}

	public function get_products_array($order) {
		$items = $order->get_items();
		$products = array();
		foreach ($items as $item) {
			$quantity = $item->get_quantity();
			$product = $item->get_product();
			$images_arr = wp_get_attachment_image_src($product->get_image_id(), array('72', '72'), false);
			$image = null;
			if ($images_arr !== null && $images_arr[0] !== null) {
				$image = $images_arr[0];
				if (is_ssl()) {
					$image = str_replace('http://', 'https://', $image);
				}
			}
			$p = array(
				'productID' => $product->get_id(),
				'quantity' => (int) $quantity,
				'price' => (int) $product->get_price(),
				'productName' => $product->get_title(),
				'productURL' => get_permalink($product->get_id()),
				'image' => $image,
			);
			array_push($products, $p);
		}
		return $products;
	}

	public function get_ips() {
		$ips = [];
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			array_push($ips, $_SERVER['HTTP_CLIENT_IP']);
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			array_push($ips, $_SERVER['HTTP_X_FORWARDED_FOR']);
		} else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
			array_push($ips, $_SERVER['HTTP_X_FORWARDED']);
		} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			array_push($ips, $_SERVER['HTTP_FORWARDED_FOR']);
		} else if (isset($_SERVER['HTTP_FORWARDED'])) {
			array_push($ips, $_SERVER['HTTP_FORWARDED']);
		} else if (isset($_SERVER['REMOTE_ADDR'])) {
			array_push($ips, $_SERVER['REMOTE_ADDR']);
		}
		return $ips;
	}

	public function handle_error($message, $err, $data = null) {
		$this->log($message, $err);
	}

	public function log($message, $data = null) {
		$pluginlog = plugin_dir_path(__FILE__).'debug.log';
        error_log($message . '\n', 3, $pluginlog);
	}
}

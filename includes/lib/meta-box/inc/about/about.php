<?php
/**
 * Add about page for the Meta Box plugin.
 *
 * @package Meta Box
 */

/**
 * About page class.
 */
class RWMB_About {
	/**
	 * Plugin data.
	 *
	 * @var array
	 */
	protected $plugin;

	/**
	 * Init hooks.
	 */
	public function init() {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			include ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$this->plugin = get_plugin_data( RWMB_DIR . 'meta-box.php' );

		// Add links to about page in the plugin action links.
		add_filter( 'plugin_action_links_meta-box/meta-box.php', array( $this, 'plugin_links' ) );

		// Add a hidden about page.
		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_action( 'admin_head', array( $this, 'hide_page' ) );

		// Enqueue scripts and styles for about page.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// Redirect to about page after activation.
		add_action( 'activated_plugin', array( $this, 'redirect' ), 10, 2 );

		if ( isset( $_GET['page'] ) && 'meta-box-about' === $_GET['page'] ) {
			add_filter( 'admin_footer_text', array( $this, 'change_footer_text' ) );
		}
	}

	/**
	 * Add links to About page.
	 *
	 * @param array $links Array of plugin links.
	 *
	 * @return array
	 */
	public function plugin_links( $links ) {
		$links[] = '<a href="' . esc_url( admin_url( 'index.php?page=meta-box-about' ) ) . '">' . esc_html__( 'About', 'vsstemmart' ) . '</a>';
		return $links;
	}

	/**
	 * Register admin page.
	 */
	public function register_page() {
		add_dashboard_page(
			__( 'Welcome to Meta Box', 'vsstemmart' ),
			__( 'Welcome to Meta Box', 'vsstemmart' ),
			'activate_plugins',
			'meta-box-about',
			array( $this, 'render' )
		);
	}

	/**
	 * Hide about page from the admin menu.
	 */
	public function hide_page() {
		remove_submenu_page( 'index.php', 'meta-box-about' );
	}

	/**
	 * Render admin page.
	 */
	public function render() {
		?>
		<div class="wrap">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="about-wrap">
							<?php include dirname( __FILE__ ) . '/sections/welcome.php'; ?>
							<?php include dirname( __FILE__ ) . '/sections/tabs.php'; ?>
							<?php include dirname( __FILE__ ) . '/sections/getting-started.php'; ?>
							<?php include dirname( __FILE__ ) . '/sections/extensions.php'; ?>
							<?php include dirname( __FILE__ ) . '/sections/support.php'; ?>
						</div>
					</div>
					<div id="postbox-container-1" class="postbox-container">
						<?php include dirname( __FILE__ ) . '/sections/newsletter.php'; ?>
						<?php include dirname( __FILE__ ) . '/sections/upgrade.php'; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Enqueue CSS and JS.
	 */
	public function enqueue() {
		$screen = get_current_screen();
		if ( 'dashboard_page_meta-box-about' !== $screen->id ) {
			return;
		}
		wp_enqueue_style( 'meta-box-about', RWMB_URL . 'inc/about/css/style.css' );
		wp_enqueue_script( 'meta-box-about', RWMB_URL . 'inc/about/js/script.js', array( 'jquery' ), '', true );
	}

	/**
	 * Change WordPress footer text on about page.
	 */
	public function change_footer_text() {
		$allowed_html = array(
			'a'      => array(
				'href'   => array(),
				'target' => array(),
			),
			'strong' => array(),
		);

		// Translators: %1$s - link to review form.
		echo wp_kses( sprintf( __( 'Please rate <strong>Meta Box</strong> <a href="%1$s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%1$s" target="_blank">WordPress.org</a> to help us spread the word. Thank you from the Meta Box team!', 'vsstemmart' ), 'https://wordpress.org/support/view/plugin-reviews/meta-box?filter=5#new-post' ), $allowed_html );
	}

	/**
	 * Redirect to about page after Meta Box has been activated.
	 *
	 * @param string $plugin       Path to the main plugin file from plugins directory.
	 * @param bool   $network_wide Whether to enable the plugin for all sites in the network
	 *                             or just the current site. Multisite only. Default is false.
	 */
	public function redirect( $plugin, $network_wide ) {
		if ( ! $network_wide && 'meta-box/meta-box.php' === $plugin && ! $this->is_bundled() ) {
			wp_safe_redirect( admin_url( 'index.php?page=meta-box-about' ) );
			die;
		}
	}

	/**
	 * Check if Meta Box is bundled by TGM Activation Class.
	 */
	protected function is_bundled() {
		// @codingStandardsIgnoreLine
		foreach ( $_REQUEST as $key => $value ) {
			if ( false !== strpos( $key, 'tgmpa' ) || false !== strpos( $value, 'tgmpa' ) ) {
				return true;
			}
		}
		return false;
	}
}

<?php
namespace WPC;

// use Elementor\Plugin; ?????
class Widget_Loader {

	private static $_instance = null;

	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	private function include_widgets_files() {
		require_once(__DIR__ . '/widgets/featured-listing.php');
	}

	public function register_widgets() {

		$this->include_widgets_files();

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Listing());

	}

	public function __construct(){
		add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets'], 99);
	}

	public function enqueue_featured_listing_style() {
		require_once(__DIR__ . '/widgets/featured-listing-style.css');
	}
}

// Instantiate Plugin Class
Widget_Loader::instance();
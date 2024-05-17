<?php



class Actwall {

	var $version = '020';

	/**
	 *	Constructor
	 */
	public function __construct() {

		add_action('after_setup_theme', array($this, 'setup'));
    add_action('wp_enqueue_scripts', array($this, 'scripts_styles'), 99);

		require get_template_directory() . '/classes/members.php';
		require get_template_directory() . '/classes/locations.php';
		require get_template_directory() . '/classes/photos.php';
		require get_template_directory() . '/classes/products.php';
		require get_template_directory() . '/classes/home.php';
		require get_template_directory() . '/classes/cadres.php';
		require get_template_directory() . '/classes/formats.php';
		require get_template_directory() . '/classes/ventes.php';

		require get_template_directory() . '/classes/options.php';
		require get_template_directory() . '/classes/pages.php';
		require get_template_directory() . '/classes/shows.php';
		require get_template_directory() . '/classes/publications.php';
		require get_template_directory() . '/classes/series.php';
		require get_template_directory() . '/classes/evenements.php';

	}

	/**
	 *	Theme Setup
	 */
	public function setup() {

		load_theme_textdomain( 'actwall', get_stylesheet_directory() . '/languages' );

		add_theme_support( 'post-thumbnails' );
    add_theme_support( 'align-wide' );

		add_editor_style();

		register_nav_menus(array(
			'top_menu' => 'Top Menu',
			'more_menu' => 'More Menu',
			'footer_menu' => 'Footer Menu'
		));

		add_action('sublanguage_custom_switch', array($this, 'language_switch'), 10, 2);

		add_action('karma_fields_post_updated', array($this, 'relevanssi_update_post'));

	}

	/**
	 *	Print theme styles and scripts
	 */
	public function scripts_styles() {

    wp_dequeue_style( 'wp-block-library' );


		// disable cache !
		$this->version = date('his');


		wp_enqueue_style('stylesheet', get_stylesheet_uri(), array(), $this->version);

		wp_enqueue_script('cookies', get_stylesheet_directory_uri().'/js/cookies.js', array(), $this->version, false);
		wp_enqueue_script('sticky', get_stylesheet_directory_uri().'/js/sticky.js', $this->version, false);
		wp_enqueue_script('abduct', get_stylesheet_directory_uri().'/js/abduct.js', $this->version, false);
    // wp_enqueue_script('tinyAnimate', get_stylesheet_directory_uri().'/js/TinyAnimate.js', array(), $this->version, true);
    wp_enqueue_script('tracker', get_stylesheet_directory_uri().'/js/tracker.js', array(), $this->version, false);
    wp_enqueue_script('accordeon', get_stylesheet_directory_uri().'/js/accordeon.js', $this->version, false);


		wp_enqueue_script('template-single-filters', get_stylesheet_directory_uri().'/js/template-filters.js', array(), $this->version, false);
		wp_enqueue_script('template-single-home', get_stylesheet_directory_uri().'/js/template-home.js', array('template-single-filters'), $this->version, false);
		wp_enqueue_script('template-single-worktosale', get_stylesheet_directory_uri().'/js/template-worktosale.js', array('template-single-home'), $this->version, false);


		wp_enqueue_script('template-single-product', get_stylesheet_directory_uri().'/js/template-single-product.js', array(), $this->version, true);
		// wp_enqueue_script('template-single-viewinroom', get_stylesheet_directory_uri().'/js/template-viewinroom.js', $this->version, true);


	}

	/**
	 * @hook 'sublanguage_custom_switch'
	 */
	public function language_switch($languages, $sublanguage) {

		include get_stylesheet_directory().'/include/language-switch.php';

	}


	/**
	 * update relevanssi plugin when post meta is edited from karma_fields
	 *
	 * @hook 'karma_fields_post_updated'
	 */
	public function relevanssi_update_post($id) {

		if (function_exists('relevanssi_insert_edit')) {

			relevanssi_insert_edit($id);

		}

	}


}

global $actwall;
$actwall = new Actwall;

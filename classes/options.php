<?php




class Actwall_Options {

  /**
   * constructor
   */
  public function __construct() {

    if (is_admin()) {

			add_action('admin_menu', array($this, 'admin_menu'));

		}

    add_action('karma_fields_init', array($this, 'karma_fields_init'));

  }

  /**
	 *	Create admin menu
	 */
	public function admin_menu() {

    add_options_page(
      'Actwall Settings',
			'Actwall Settings',
      'manage_options',
      'actwall-settings',
      array($this, 'print_settings')
    );

    add_submenu_page(
      'edit.php?post_type=member',
      'Display options',
			'Display options',
      'read',
			'display_options',
			array($this, 'print_display_options')
    );

	}



  public function print_settings() {

    echo '<h1>Actwall Settings</h1>';

    // do_action('karma_fields_option_field', 'actwall', array(
    //   'children' => array(
    //     array(
    //       'label' => 'Photography term ID',
    //       'type' => 'input',
    //       'key' => 'photography_term'
    //     ),
    //     array(
    //       'type' => 'submit',
    //       'style' => 'width:200px'
    //     )
    //   )
    // ));

  }

  public function print_display_options() {

  }


  /**
   * @hook karma_fields_init
   */
  public function karma_fields_init($karma_fields) {

    $karma_fields->register_menu_item('display_options', '?post_type=member&page=display_options');

    $karma_fields->register_table('display_options', array(
			'header' => array(
        'type' => 'header',
				'title' => 'Display Options',
        'children' => array(
          'title',
          'close'
        )
      ),
			'body' => array(
        'id' => 'actwall',
				'type' => 'form',
				'driver' => 'options',
        'children' => array(
          array(
            'type' => 'group',
            'key' => 'option_value',
            'children' => array(
              array(
                'type' => 'input',
                'label' => 'Home image size (em/cm)',
                'placeholder' => '0.3',
                'key' => 'home_image_size',
                'width' => '20em'
              ),
              array(
                'type' => 'input',
                'label' => 'Home min image height (cm)',
                'key' => 'home_min_image_height',
                'width' => '20em'
              ),
              array(
                'type' => 'input',
                'label' => 'Home max image height (cm)',
                'key' => 'home_max_image_height',
                'width' => '20em'
              ),
              array(
                'type' => 'input',
                'label' => 'Home margin horrizontal (cm)',
                'key' => 'home_margin_h',
                'width' => '20em'
              ),
              array(
                'type' => 'input',
                'label' => 'Home margin vertical (cm)',
                'key' => 'home_margin_v',
                'width' => '20em'
              ),
              array(
                'type' => 'input',
                'label' => 'Produit image size (em/cm)',
                'placeholder' => '0.3',
                'key' => 'produit_image_size',
                'width' => '20em'
              ),
              array(
                'type' => 'input',
                'label' => 'Produit min image height (cm)',
                'key' => 'produit_min_image_height',
                'width' => '20em'
              ),
              array(
                'type' => 'input',
                'label' => 'Produit max image height (cm)',
                'key' => 'prpoduit_max_image_height',
                'width' => '20em'
              ),
              array(
                'type' => 'input',
                'label' => 'Série image size (em/cm)',
                'placeholder' => '0.3',
                'key' => 'serie_image_size',
                'width' => '20em'
              ),
              array(
                'type' => 'input',
                'label' => 'Série min image height (cm)',
                'key' => 'serie_min_image_height',
                'width' => '20em'
              ),
              array(
                'type' => 'input',
                'label' => 'Série max image height (cm)',
                'key' => 'serie_max_image_height',
                'width' => '20em'
              )


            )
          )
        )
      ),
      'footer' => array(
				'children' => array(
					'save',
          'separator',
					'undo',
					'redo'
				)
			)
    ));

  }


}


new Actwall_Options;

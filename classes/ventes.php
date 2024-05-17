<?php




class Actwall_Ventes {

  /**
   * constructor
   */
  public function __construct() {

    if (is_admin()) {

			add_action('admin_menu', array($this, 'admin_menu'));
			add_action('init', array($this, 'create_table'));
      // add_action('init', array($this, 'create_cadre_types_table'));


		}

    // add_action('rest_api_init', array($this, 'rest_api_init'));
    add_action('karma_fields_init', array($this, 'karma_fields_init'));

  }

  // /**
	//  *	@hook 'rest_api_init'
	//  */
	// public function rest_api_init() {
  //
	// 	// register_rest_route('maxitype/v1', '/packages', array(
	// 	// 	'methods' => 'GET',
	// 	// 	'callback' => array($this, 'rest_packages'),
	// 	// 	'permission_callback' => '__return_true',
  //   //   'args' => array(
	// 	// 		'typeface_id' => array(
	// 	// 			'required' => true
	// 	// 		)
	//   //   )
	// 	// ));
  //   //
  //   // register_rest_route('maxitype/v1', '/package', array(
	// 	// 	'methods' => 'GET',
	// 	// 	'callback' => array($this, 'rest_package'),
	// 	// 	'permission_callback' => '__return_true',
  //   //   'args' => array(
	// 	// 		'id' => array(
	// 	// 			'required' => true
	// 	// 		)
	//   //   )
	// 	// ));
  //
  // }


  /**
	 *	create ventes table
	 */
	public function create_table() {
		global $wpdb;

		$table_version = '003';

		if ($table_version !== get_option('actwall_ventes_table_version')) {

			$charset_collate = '';

			if (!empty($wpdb->charset)){
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}

			if (!empty($wpdb->collate)){
				$charset_collate .= " COLLATE $wpdb->collate";
			}

			$mysql = "CREATE TABLE {$wpdb->prefix}ventes (
				`id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `member_id` bigint(12) NOT NULL,
        `product_id` bigint(12) NOT NULL,
        `format_id` bigint(12) NOT NULL,
        `produit` tinyint(1) NOT NULL,
        `prix` float(7, 2) NOT NULL,
				`trash` tinyint(1) NOT NULL,
        KEY member_id (member_id)
        KEY product_id (product_id)
        KEY format_id (format_id)
			) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($mysql);

			update_option('actwall_ventes_table_version', $table_version);
		}

  }

  // /**
	//  *	create cadre types table
	//  */
	// public function create_cadre_types_table() {
	// 	global $wpdb;
  //
  //
  //   $table_version = '001';
  //
	// 	if ($table_version !== get_option('actwall_cadre_types_table_version')) {
  //
	// 		$charset_collate = '';
  //
	// 		if (!empty($wpdb->charset)){
	// 			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	// 		}
  //
	// 		if (!empty($wpdb->collate)){
	// 			$charset_collate .= " COLLATE $wpdb->collate";
	// 		}
  //
	// 		$mysql = "CREATE TABLE {$wpdb->prefix}cadre_types (
	// 			`id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  //       `name` varchar(255) NOT NULL,
	// 			`trash` tinyint(1) NOT NULL
	// 		) $charset_collate;";
  //
	// 		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	// 		dbDelta($mysql);
  //
	// 		update_option('actwall_cadre_types_table_version', $table_version);
	// 	}
  //
	// }


  /**
	 *	Create admin menu
	 */
	public function admin_menu() {

    add_submenu_page(
      'edit.php?post_type=member',
      'Ventes',
			'Ventes',
      'read',
			'ventes',
			array($this, 'print_ventes')
    );

	}

  public function print_ventes() {

  }

  /**
   * @hook karma_fields_init
   */
  public function karma_fields_init($karma_fields) {

    $karma_fields->register_driver(
			'ventes',
			get_template_directory().'/drivers/driver-ventes.php',
			'Actwall_Driver_Ventes',
      array(),
      array()
		);

    $karma_fields->register_menu_item('ventes', 'edit.php?post_type=member&page=ventes');

    $karma_fields->register_table('ventes', array(
      'header' => array(
        'type' => 'group',
        'children' => array(
          array(
            'type' => 'header',
            'title' => 'Ventes'
          ),
          array(
            'type' => 'group',
            'display' => 'flex',
            'children' => array(
              array(
                'type' => 'dropdown',
                'label' => 'Member',
                'key' => 'member_id',
                'options' => array(array('id' => '', 'name' => '-')),
                'driver' => 'posts',
                'params' => array(
                  'post_type' => 'member',
                  'orderby' => 'post_title',
                  'order' => 'asc',
                  // 'post_parent' => array('getValue', 'member_id'),
                  'post_status' => 'publish'
                ),
                'width' => '10em'
              ),
              array(
                'type' => 'dropdown',
                'label' => 'Série',
                'key' => 'serie_id',
                'options' => array(array('id' => '', 'name' => '-')),
                'driver' => 'posts',
                'params' => array(
                  'post_type' => 'serie',
                  'post_parent' => array('getValue', 'member_id'),
                  'post_status' => 'publish'
                ),
                'width' => '10em'
              ),

              array(
                'type' => 'dropdown',
                'label' => 'Image',
                'key' => 'product_id',
                'options' => array(array('id' => '', 'name' => '-')),
                'driver' => 'posts',
                'params' => array(
                  'post_type' => 'product',
                  'post_parent' => array('getValue', 'member_id'),
                  'post_status' => 'publish'
                ),
                'width' => '10em'
              ),
              array(
                'type' => 'dropdown',
                'label' => 'Format',
                'key' => 'format_id',
                'options' => array(array('id' => '', 'name' => '-')),
                'driver' => 'formats',
                'params' => array(
                  'serie_id' => array('getParam', 'serie_id')
                ),
                'width' => '10em'
              ),

              array(
                'type' => 'separator'
              ),

              array(
                'label' => 'Search',
                'key' => 'search',
                'type' => 'input',
                'width' => '30em'
              )
            )
          )
        )
			),
      'body' => array(
        'type' => 'grid',
        'driver' => 'ventes',
        'params' => array(
          // 'member_id' => array('getValue', 'id')
        ),
        'children' => array(
          array(
            'type' => "media",
            'id' => array('queryValue', 'posts', array('getValue', 'product_id'), 'attachments'),
            'driver' => 'medias',
            'width' => '10em',
            'display' => 'thumb'
          ),
          array(
            'label' => 'Time',
            'type' => 'text',
            'content' => array('date', array('getValue', 'time')),
            'width' => 'auto'
          ),
          array(
            'label' => 'Serie',
            'type' => 'text',
            'content' => array('queryValue', 'posts', array('queryValue', 'posts', array('getValue', 'product_id'), 'serie_id'), 'post_title'),
            'width' => '0.5fr'
          ),
          array(
            'label' => 'Title',
            'type' => 'text',
            'content' => array('queryValue', 'posts', array('getValue', 'product_id'), 'post_title'),
            'width' => '0.5fr'
          ),
          array(
            'label' => 'Photographe',
            'type' => 'text',
            'content' => array('queryValue', 'posts', array('getValue', 'member_id'), 'post_title'),
            'width' => '0.5fr'
          ),
          array(
            'label' => 'Format',
            'type' => 'text',
            'content' => array('queryValue', 'formats', array('getValue', 'format_id'), 'name'),
            'width' => 'auto'
          )



          // array(
          //   'type' => 'text',
          //   'content' => array('getValue', '_min_price'),
          //   'label' => 'Prix Tirage',
          //   'width' => '6em'
          // ),
          // array(
          //   'type' => 'text',
          //   'content' => array('getValue', '_max_price'),
          //   'label' => 'Prix Encadré',
          //   'width' => '6em'
          // )
          // array(
          //   'type' => 'checkbox',
          //   'key' => 'produit',
          //   'label' => 'Produit',
          //   'width' => '1fr'
          // )


        ),
        'modal' => array(
          'width' => '40em',
          'children' => array(
            array(
              'type' => 'date',
              'key' => 'time',
              'label' => 'Date',
              'default' => array('replace', '%-%-%', '%', array('year'), array('month'), array('day')),
              'width' => 'auto'
            ),

            array(
              'type' => 'dropdown',
              'label' => 'Image',
              'key' => 'product_id',
              'options' => array(array('id' => '', 'name' => '-')),
              'driver' => 'posts',
              'params' => array(
                'post_type' => 'product',
                'post_parent' => array('getValue', 'member_id'),
                'post_status' => 'publish'
              ),
              'default' => array('getParam', 'product_id'),
              'width' => '10em'
            ),
            array(
              'type' => 'dropdown',
              'label' => 'Format',
              'key' => 'format_id',
              'options' => array(array('id' => '', 'name' => '-')),
              'driver' => 'formats',
              'params' => array(
                // 'member_id' => array('queryValue', 'ventes', array('getValue', 'id'), 'member_id'),
                // 'ids' => array('join', array('queryValue', 'posts', array('getValue', 'product_id'), 'format_id'), ',')
                'serie_id' => array('queryValue', 'posts', array('getValue', 'product_id'), 'serie_id')
              ),
              'default' => array('getParam', 'format_id'),
              'width' => '10em'
            ),

            array(
              'type' => 'input',
              'key' => 'prix',
              'label' => 'Prix',
              'default' => array('getParam', 'prix'),
              'width' => '6em'
            )
            // array(
            //   'type' => 'text',
            //   'content' => array('getValue', 'product_id'),
            //   'label' => 'Prix',
            //   'width' => '6em'
            // ),
            // array(
            //   'type' => 'checkbox',
            //   'key' => 'produit',
            //   'label' => 'Produit',
            //   'width' => '1fr'
            // )
          )
        )
      )

      // 'filters' => array(
      //
      // )
    ));






  }

}


new Actwall_Ventes;

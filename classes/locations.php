<?php




class Actwall_Locations {

  /**
   * constructor
   */
  public function __construct() {

    if (is_admin()) {

			add_action('admin_menu', array($this, 'admin_menu'));
			add_action('init', array($this, 'create_table'));

		}

    add_action('rest_api_init', array($this, 'rest_api_init'));
    add_action('karma_fields_init', array($this, 'karma_fields_init'));

  }

  /**
	 *	@hook 'rest_api_init'
	 */
	public function rest_api_init() {

		// register_rest_route('maxitype/v1', '/packages', array(
		// 	'methods' => 'GET',
		// 	'callback' => array($this, 'rest_packages'),
		// 	'permission_callback' => '__return_true',
    //   'args' => array(
		// 		'typeface_id' => array(
		// 			'required' => true
		// 		)
	  //   )
		// ));
    //
    // register_rest_route('maxitype/v1', '/package', array(
		// 	'methods' => 'GET',
		// 	'callback' => array($this, 'rest_package'),
		// 	'permission_callback' => '__return_true',
    //   'args' => array(
		// 		'id' => array(
		// 			'required' => true
		// 		)
	  //   )
		// ));

  }


  /**
	 *	create font table
	 */
	public function create_table() {
		global $wpdb, $karma;

		$table_version = '002';

		if ($table_version !== get_option('actwall_locations_table_version')) {

			$charset_collate = '';

			if (!empty($wpdb->charset)){
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}

			if (!empty($wpdb->collate)){
				$charset_collate .= " COLLATE $wpdb->collate";
			}

			$mysql = "CREATE TABLE {$wpdb->prefix}locations (
				`id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255) NOT NULL,
        -- `country` varchar(255) NOT NULL,
				`trash` tinyint(1) NOT NULL
			) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($mysql);

			update_option('actwall_locations_table_version', $table_version);
		}

	}


  /**
	 *	Create admin menu
	 */
	public function admin_menu() {

    // add_submenu_page(
    //   'edit.php?post_type=typeface',
    //   'Packages',
		// 	'Packages',
    //   'read',
		// 	'packages',
		// 	array($this, 'print_packages')
    // );

	}

  /**
   * @hook karma_fields_init
   */
  public function karma_fields_init($karma_fields) {

    $karma_fields->register_driver(
			'locations',
			get_template_directory().'/drivers/driver-locations.php',
			'Actwall_Driver_Locations',
      array(),
      array()
		);

    // $karma_fields->register_menu_item('packages', '?post_type=typeface&page=packages');

    $karma_fields->register_table('locations', array(
      'header' => array(
				'title' => 'Locations'

			),
      'body' => array(
				'type' => 'grid',
				'driver' => 'locations',
        'params' => array(
          'orderby' => 'name',
          'order' => 'asc',
					'ppp' => 100
				),
        // 'width' => '30em',
        'children' => array(
          array(
            'type' => 'rowIndex'
          ),
          // array(
          //   'label' => 'City',
          //   'width' => '1fr',
          //   'type' => 'input',
          //   'key' => 'name'
          // )
          array(
            'label' => 'City',
            'width' => '1fr',
            'type' => 'text',
            'value' => array('getValue', 'name')
          )
        ),
        'modal' => array(
          'children' => array(
            array(
              'label' => 'City',
              'width' => '1fr',
              'type' => 'input',
              'key' => 'name'
            )
          )
        )
      ),
      'filters' => array(
        'children' => array(
          array(
            'label' => 'Search',
            'key' => 'search',
            'type' => 'input',
            'width' => '30em'
          )
        )
      )
    ));


  }

  // /**
  //  * @rest 'wp-json/maxitype/v1/packages'
  //  */
  // public function rest_packages($request) {
  //   global $wpdb;
  //
  //   $typeface_id = $request->get_param('typeface_id');
  //   $typeface_id = intval($typeface_id);
  //
  //   $results = $wpdb->get_results(
  //     "SELECT pk.id, pk.name, pk.price, count(pr.id) AS 'num', pk.description, p.post_title AS 'typeface' FROM {$wpdb->prefix}packages AS pk
  //     LEFT JOIN {$wpdb->prefix}pack_rel AS pr ON (pr.pack_id = pk.id)
  //     LEFT JOIN $wpdb->posts AS p ON (p.ID = pk.typeface_id)
  //     WHERE pk.trash = 0 AND pk.typeface_id = $typeface_id
  //     GROUP BY pk.id
  //     ORDER BY pk.position ASC, pk.name ASC"
  //   );
  //
  //   return $results;
  // }
  //
  // /**
  //  * @rest 'wp-json/maxitype/v1/package'
  //  */
  // public function rest_package($request) {
  //   global $wpdb;
  //
  //   $id = $request->get_param('id');
  //   $id = intval($id);
  //
  //   $results = $wpdb->get_row(
  //     "SELECT pk.id, pk.name, pk.price, pk.description, p.post_title AS 'typeface' FROM {$wpdb->prefix}packages AS pk
  //     LEFT JOIN $wpdb->posts AS p ON (p.ID = pk.typeface_id)
  //     WHERE pk.id = $id
  //     GROUP BY pk.id
  //     LIMIT 1"
  //   );
  //
  //   return $results;
  // }



}


new Actwall_Locations;

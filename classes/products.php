<?php




class Actwall_Products {

  /**
   * constructor
   */
  public function __construct() {

    // add_action('actwall_photo_body', array($this, 'print_product_body')); -> moved in home.php

    add_action('init', array($this, 'register'));

    if (is_admin()) {

			add_action('admin_menu', array($this, 'admin_menu'));

      // add_action('init', array($this, 'create_table'));

		}


    // add_action('rest_api_init', array($this, 'rest_api_init'));
    add_action('karma_fields_init', array($this, 'karma_fields_init'));



    // add_filter('attachment_link', array($this, 'attachment_link'), 10, 2);


    // add_action('template_redirect', array($this, 'custom_redirects') );


  }


  /**
	 *	create formats table
	 */
	public function create_table() {
		global $wpdb;

		$table_version = '001';

		if ($table_version !== get_option('actwall_editions_table_version')) {

			$charset_collate = '';

			if (!empty($wpdb->charset)){
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}

			if (!empty($wpdb->collate)){
				$charset_collate .= " COLLATE $wpdb->collate";
			}

			$mysql = "CREATE TABLE {$wpdb->prefix}editions (
				`id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `product_id` bigint(12) NOT NULL,
        `format_id` bigint(12) NOT NULL,
        `framing` varchar(255) NOT NULL,
        `specifications` varchar(255) NOT NULL,
        `price` int(9) NOT NULL,
        `nb` int(9) NOT NULL,
        `nb_produit` int(9) NOT NULL,
				`trash` tinyint(1) NOT NULL,
        KEY product_id (product_id),
        KEY format_id (format_id)
			) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($mysql);

			update_option('actwall_editions_table_version', $table_version);
		}

  }


  // public function custom_redirects() {
  //   global $wp_query;
  //
  //   // -> redirect images that have no image type
  //
  //
  //
  //   if (is_attachment()) {
  //
  //     $post = get_queried_object();
  //
  //     $terms = get_the_terms($post, 'image-type');
  //
  //     if (empty($terms)) {
  //
  //       if ($post->post_parent) {
  //
  //         $member = get_post($post->post_parent);
  //
  //         if ($member && $member->post_type === 'member') {
  //
  //           $permalink = get_permalink($member);
  //           wp_redirect($permalink);
  //           exit;
  //
  //         }
  //
  //       }
  //
  //
  //       // -> 404
  //
  //       $wp_query->set_404();
  //       status_header( 404 );
  //       get_template_part( 404 );
  //       exit();
  //
  //     }
  //
  //   }
  //
  //

  // }





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
	 * @hook init
	 */
	public function register() {

    register_post_type('product', array(
      'labels'             => array(
        'name' => 'Produits',
        'singular_name' => 'Produit'
      ),
      'public'             => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      // 'rewrite'            => array(
      //   'slug' => 'member'
      // ),
      'capability_type'    => 'post',
      'has_archive'        => false,
      'hierarchical'       => false,
      // 'menu_position'      => null,
      'supports'           => array('title', 'editor', 'excerpt'),
      'show_in_rest' => false
      // 'taxonomies' => array('module-category')
    ));

    register_taxonomy('image-type', array('attachment', 'product'), array(
      'label'        => 'Image type',
      'public'       => true,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'rewrite'      => false,
      'hierarchical' => true
    ));

    register_taxonomy('photo-type', array('attachment', 'product'), array(
      'label'        => 'Photo type',
      'public'       => true,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'rewrite'      => false,
      'hierarchical' => true
    ));

    register_taxonomy('print', array('attachment', 'product'), array(
      'label'        => 'Print type',
      'public'       => true,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'rewrite'      => false,
      'hierarchical' => true
    ));


  }


  /**
	 *	Create admin menu
	 */
	public function admin_menu() {

    add_submenu_page(
      'edit.php?post_type=member',
      'Products',
			'Products',
      'read',
			'products',
			array($this, 'print_product')
    );

	}


  public function print_product() {

  }

  /**
   * @hook karma_fields_init
   */
  public function karma_fields_init($karma_fields) {


    $karma_fields->register_menu_item('products', '?post_type=product');

    $karma_fields->register_table('products', array(
      'header' => array(
        'title' => 'Products',

        'type' => 'group',
        'children' => array(
          array(
            'type' => 'header',
            'title' => 'Products'
          ),
          array(
            'type' => 'group',
            'display' => 'flex',
            'children' => array(
              // array(
              //   'label' => 'Image Type',
              //   'type' => 'dropdown',
              //   'key' => 'image-type',
              //   'options' => array(array('id' => '', 'name' => '–')),
              //   'driver' => 'taxonomy',
              //   'params' => array('taxonomy' => 'image-type')
              // ),
              array(
                'label' => 'Member',
                'type' => 'dropdown',
                'key' => 'post_parent',
                'options' => array(array('id' => '', 'name' => '–')),
                'driver' => 'posts',
                'params' => array('post_type' => 'member', 'post_status' => 'publish')
              ),
              array(
                'label' => 'Série',
                'type' => 'dropdown',
                'key' => 'serie_id',
                'options' => array(array('id' => '', 'name' => '–'), array('id' => '0', 'name' => '[Aucune]')),
                'driver' => 'posts',
                'params' => array('post_type' => 'serie', 'post_status' => 'publish', 'post_parent' => array('getValue', 'post_parent'))
              ),
              array(
                'type' => 'input',
                'label' => 'Recherche',
                'key' => 'search',
                'style' => 'flex:1',
                'width' => '20em'
              )
            )
          )
        )
      ),
      'body' => array(
        'type' => 'grid',
        'driver' => 'posts',
        'params' => array(
          'post_type' => 'product',
          'ppp' => 100
        ),

        'children' => array(

          // array(
          //   'label' => 'images',
          //   'type' => 'media',
          //   'driver' => 'medias',
          //   'id' => array('getValue', 'attachments'),
          //   'display' => 'thumb',
          //   'width' => '6em',
          //   'height' => '6em'
          // ),
          // array(
          //   'type' => "media",
          //   'id' => array('queryValue', 'posts', array('getValue', 'id'), 'attachments'),
          //   'driver' => 'medias',
          //   'width' => '10em',
          //   'display' => 'thumb'
          // ),
          array(
            'label' => 'Title',
            'type' => 'text',
            'content' => array('getValue', 'post_title'),
            'width' => '1fr'
          ),
          array(
            'label' => 'Member',
            'type' => 'text',
            'content' => array('queryValue', 'posts', array('getValue', 'post_parent'), 'post_title')
          ),
          // array(
          //   'label' => 'Image type',
          //   'type' => 'text',
          //   'content' => array('queryValue', 'taxonomy', array('getValue', 'image-type'), 'name')
          // ),
          array(
            'label' => 'Série',
            'type' => 'text',
            'content' => array('queryValue', 'posts', array('getValue', 'serie_id'), 'post_title')
          ),
          array(
            'label' => 'Série ID',
            'type' => 'text',
            'content' => array('getValue', 'serie_id')
          ),
          array(
            'type' => 'links',
            'label' => 'Série',
            'table' => 'serie',
            'params' => array('id' => array('getValue', 'serie_id')),
            'content' => array('?', array('getValue', 'serie_id'), array('||', array('queryValue', 'posts', array('getValue', 'serie_id'), 'post_title'), '[sans nom]'), '')
          )
        ),
        'modal' => array(
          'width' => '40em',
          'children' => array(
            array(
              'label' => 'Member',
              'type' => 'dropdown',
              'key' => 'post_parent',
              'options' => array(array('id' => '', 'name' => '–')),
              'driver' => 'posts',
              'params' => array('post_type' => 'member', 'post_status' => 'publish')
            ),
            array(
              'label' => 'Série',
              'type' => 'dropdown',
              'key' => 'serie_id',
              'options' => array(array('id' => '', 'name' => '–')),
              'driver' => 'posts',
              'params' => array('post_type' => 'serie', 'post_status' => 'publish')
            ),


            array(
              'type' => 'group',
              'width' => 'auto',
              'children' => array(
                array(
                  'type' => 'file',
                  'key' => 'attachments',
                  'max' => 1,
                  'label' => 'File',
                  // 'uploader' => 'wp',
                  'width' => 'auto',
                  'params' => array('parent' => array('getValue', 'post_parent'))
                ),
                array(
                  'type' => 'dropdown',
                  'key' => 'post_status',
                  'label' => 'Status',
                  'options' => array(array('id' => 'draft', 'name' => 'Draft'), array('id' => 'publish', 'name' => 'Publish'))
                ),
                array(
                  'type' => 'text',
                  'content' => array('getValue', 'id')
                )
              )
            ),

            array(
              'type' => 'group',
              'width' => '1fr',
              'children' => array(

                array(
                  'type' => 'input',
                  'key' => 'name',
                  'label' => 'Titre',
                  'default' => array('queryValue', 'posts', array('queryValue', 'posts', array('getValue', 'id'), 'serie_id'), 'post_title'),
                  'placeholder' => array('queryValue', 'posts', array('queryValue', 'posts', array('getValue', 'id'), 'serie_id'), 'post_title')
                  // 'width' => '18em'
                ),
                array(
                  'type' => 'tinymce',
                  'key' => 'post_content',
                  'label' => 'Description',
                  'translatable' => true,
                  'header' => array(
                    'children' => array('bold', 'italic')
                  )
                ),
                array(
                  'type' => 'textarea',
                  'key' => 'keywords',
                  'label' => 'Mots-clés',
                  'translatable' => true
                )
              )
            ),

            array(
              'type' => 'group',
              'width' => 'auto',
              'children' => array(
                array(
                  'key' => 'nb_tfc',
                  'type' => 'input',
                  'label' => 'Nombre',
                  'width' => '8em',
                  'placeholder' => array('queryValue', 'posts', array('queryValue', 'posts', array('getValue', 'id'), 'serie_id'), 'nb_tfc'),
                  'disabled' => array("=", array('queryValue', 'posts', array('queryValue', 'posts', array('getValue', 'id'), 'serie_id'), 'mode'), "format")
                ),

                array(
                  // 'label' => '_total_produit',
                  'type' => 'hidden',
                  'key' => '_total_produit',
                  'width' => '6em',
                  'value' => array('sum', array('getValue', 'nb_produit'))
                ),
                array(
                  // 'label' => '_min_price',
                  'type' => 'hidden',
                  'key' => '_min_price',
                  'width' => '6em',
                  'value' => array('min', array('getValue', '_prix_encadre'))
                ),
                array(
                  // 'label' => '_max_price',
                  'type' => 'hidden',
                  'key' => '_max_price',
                  'width' => '6em',
                  'value' => array('max', array('getValue', '_prix_encadre'))
                ),
                array(
                  'label' => '_min_price',
                  'type' => 'text',
                  'content' => array('getValue', '_min_price')
                ),
                array(
                  'label' => '_max_price',
                  'type' => 'text',
                  'content' => array('getValue', '_max_price')
                )
              )
            ),


            // products editions array
            array(
              'type' => 'array',

              'footer' => array(
                'children' => array(
                  array(
                    'type' => 'add',
                    'text' => 'Add edition'
                  )
                )
              ),
              'children' => array(

                array(
                  'type' => 'group',
                  'width' => '10em',
                  'children' => array(
                    array(
                      'type' => 'dropdown',
                      'label' => 'Format',
                      'key' => 'format_id',
                      'options' => array(array('id' => '', 'name' => '-')),
                      'driver' => 'formats',
                      'params' => array(
                        'serie_id' => array('queryValue', 'posts', array('request', 'getId'), 'serie_id')
                      )
                    ),
                    array(
                      'key' => 'nb',
                      'type' => 'input',
                      'label' => 'Nombre',
                      'placeholder' => array('queryValue', 'formats', array('getValue', 'format_id'), 'nb'),
                      'enabled' => array("=", array('queryValue', 'posts', array('queryValue', 'posts', array('request', 'getId'), 'serie_id'), 'mode'), "format")
                    ),

                    array(
                      'key' => 'nb_produit',
                      'type' => 'input',
                      'label' => 'Nombre produits',
                    ),
                    array(
                      'label' => 'Nombre vendu',
                      'type' => 'links',
                      'table' => 'ventes',
                      'params' => array(
                        'product_id' => array('request', 'getId'),
                        'format_id' => array('getValue', 'format_id'),
                        'member_id' => array('queryValue', 'posts', array('request', 'getId'), 'post_parent'),
                        'serie_id' => array('queryValue', 'posts', array('request', 'getId'), 'serie_id'),
                        'prix' => array('||', array('getValue', 'prix_encadre'), array('queryValue', 'formats', array('getValue', 'format_id'), 'prix_encadre'))
                      ),
                      'content' => array('getLength', array('query', 'ventes', array('format_id' => array('getValue', 'format_id'), 'product_id' => array('request', 'getId'))))
                    ),
                    array(
                      'key' => 'prix_encadre',
                      'type' => 'input',
                      // 'label' => 'Prix',
                      'label' => array('replace', 'Prix (#)', '#', array('?', array('>', array('queryValue', 'formats', array('getValue', 'format_id'), 'cadre_id'), 0), 'Encadré', 'Tirage')),
                      'placeholder' => array('queryValue', 'formats', array('getValue', 'format_id'), 'prix_encadre')
                    ),
                    array(
                      'key' => '_prix_encadre',
                      'type' => 'hidden',
                      'value' => array(
                        '||',
                        array('getValue', 'prix_encadre'),
                        array('queryValue', 'formats', array('getValue', 'format_id'), 'prix_encadre')
                      )
                    )
                  )
                ),
                array(
                  'type' => 'group',
                  'width' => '28em',
                  'children' => array(
                    array(
                      'type' => 'textarea',
                      'key' => 'framing',
                      'label' => 'Framing',
                      'height' => '5em'
                    ),
                    array(
                      'type' => 'textarea',
                      'key' => 'specifications',
                      'label' => 'Specifications',
                      'height' => '5em'
                    )

                  )
                )
              )
            )







            // array(
            // 	'type' => 'files',
            // 	'label' => 'Files',
            //   'mimetype' => 'image',
            // 	'key' => 'attachments',
            //   'driver' => 'medias',
            //   'table' => 'photos',
            //   'uploader' => 'wp'
            // ),
            // array(
						// 	'type' => 'input',
						// 	'label' => 'Title',
						// 	'key' => 'post_title'
						// ),
            //
            // array(
						// 	'type' => 'dropdown',
						// 	'label' => 'Serie',
						// 	'key' => 'serie_id',
            //   'driver' => 'posts',
            //   'params' => array(
            //     'post_type' => 'serie',
            //     'post_status' => 'publish'
            //   )
						// ),
						// array(
						// 	'type' => 'textarea',
						// 	'label' => 'Caption (post_excerpt)',
						// 	'key' => 'post_excerpt'
						// ),
            // array(
						// 	'type' => 'textarea',
						// 	'label' => 'Caption (post_content)',
						// 	'key' => 'post_content'
						// ),
            // array(
            //   'type' => 'group',
            //   'display' => 'flex',
            //   'children' => array(
            //     array(
            //       'type' => 'input',
            //       'label' => 'Edition total',
            //       'key' => 'edition_total'
            //     ),
            //     array(
            //       'type' => 'input',
            //       'label' => 'Edition sold',
            //       'key' => 'edition_sold'
            //     )
            //   )
            // ),
            // array(
						// 	'type' => 'textarea',
						// 	'label' => 'Specification',
						// 	'key' => 'specifications'
						// ),
            // array(
						// 	'type' => 'textarea',
						// 	'label' => 'Framing',
						// 	'key' => 'framing'
						// ),
            // array(
						// 	'type' => 'textarea',
						// 	'label' => 'Exhibition History',
						// 	'key' => 'exhibitions-history'
						// ),
            // array(
						// 	'type' => 'textarea',
						// 	'label' => 'Shipping',
						// 	'key' => 'shipping'
						// ),
            // array(
            //   'type' => 'input',
            //   'label' => 'Price',
            //   'key' => 'price'
            // ),
            // array(
            //   'type' => 'group',
            //   'display' => 'flex',
            //   'children' => array(
            //     array(
            //       'label' => 'Width (cm)',
            //       'type' => 'input',
            //       'key' => 'size-width'
            //     ),
            //     array(
            //       'label' => 'Height (cm)',
            //       'type' => 'input',
            //       'key' => 'size-height'
            //     )
            //   )
            // ),
            // array(
            //   'type' => 'group',
            //   'display' => 'flex',
            //   'wrap' => false,
            //   'children' => array(
            //     array(
            //       'label' => 'Margin top (cm)',
            //       'type' => 'input',
            //       'key' => 'margin-top',
            //       'placeholder' => '0'
            //     ),
            //     array(
            //       'label' => 'right (cm)',
            //       'type' => 'input',
            //       'key' => 'margin-right',
            //       'placeholder' => array('||', array('getValue', 'margin-top'), '0')
            //     ),
            //     array(
            //       'label' => 'bottom (cm)',
            //       'type' => 'input',
            //       'key' => 'margin-bottom',
            //       'placeholder' => array('||', array('getValue', 'margin-top'), '0')
            //     ),
            //     array(
            //       'label' => 'left (cm)',
            //       'type' => 'input',
            //       'key' => 'margin-left',
            //       'placeholder' => array('||', array('getValue', 'margin-right'), array('getValue', 'margin-top'), '0')
            //     )
            //   )
            // ),
            // array(
            //   'type' => 'group',
            //   'display' => 'flex',
            //   'wrap' => false,
            //   'children' => array(
            //     array(
            //       'label' => 'Padding top (cm)',
            //       'type' => 'input',
            //       'key' => 'padding-top',
            //       'placeholder' => '0'
            //     ),
            //     array(
            //       'label' => 'right (cm)',
            //       'type' => 'input',
            //       'key' => 'padding-right',
            //       'placeholder' => array('||', array('getValue', 'padding-top'), '0')
            //     ),
            //     array(
            //       'label' => 'bottom (cm)',
            //       'type' => 'input',
            //       'key' => 'padding-bottom',
            //       'placeholder' => array('||', array('getValue', 'padding-top'), '0')
            //     ),
            //     array(
            //       'label' => 'left (cm)',
            //       'type' => 'input',
            //       'key' => 'padding-left',
            //       'placeholder' => array('||', array('getValue', 'padding-right'), array('getValue', 'padding-top'), '0')
            //     )
            //   )
            // ),
            // array(
            //   'type' => 'tags',
            //   'key' => 'serie',
            //   'label' => 'Serie',
            //   'driver' => 'posts',
            //   'params' => array('post_type' => 'serie', 'post_parent' => array('getValue', 'post_parent')),
            //   'table' => 'series'
            // ),
            //
            // array(
            //   'type' => 'tags',
            //   'key' => 'photo-type',
            //   'label' => 'Photo type',
            //   'driver' => 'taxonomy',
            //   'params' => array('taxonomy' => 'photo-type'),
            //   'table' => 'taxonomy'
            // ),
            // array(
            //   'type' => 'tags',
            //   'key' => 'print',
            //   'label' => 'Print type',
            //   'driver' => 'taxonomy',
            //   'params' => array('taxonomy' => 'print'),
            //   'table' => 'taxonomy'
            // ),
            //
            // array(
            //   'type' => 'checkboxes',
            //   'key' => 'availability',
            //   'label' => 'Availability',
            //   'options' => array(
            //     array('id' => '1', 'name' => 'Already produced'),
            //     array('id' => '2', 'name' => 'Has to be produced (1-2 weeks)')
            //   )
            // ),
            // array(
            //   'type' => 'checkboxes',
            //   'key' => 'color',
            //   'label' => 'Color',
            //   'columns' => 3,
            //   'options' => array(
            //     array('id' => 'red', 'name' => 'Red'),
            //     array('id' => 'orange', 'name' => 'Orange'),
            //     array('id' => 'yellow', 'name' => 'Yellow'),
            //     array('id' => 'green', 'name' => 'Green'),
            //     array('id' => 'brown', 'name' => 'Brown'),
            //     array('id' => 'pink', 'name' => 'Pink'),
            //     array('id' => 'purple', 'name' => 'Purple'),
            //     array('id' => 'blue', 'name' => 'Blue'),
            //     array('id' => 'black', 'name' => 'Black')
            //   )
            // ),
            // array(
            //   'type' => 'tags',
            //   // 'max' => 1,
            //   'key' => 'cadre_id',
            //   'label' => 'Cadre',
            //   'table' => 'cadres',
            //   'driver' => 'cadres'
            // ),
            // array(
            //   'type' => 'tags',
            //   'key' => 'show_id',
            //   'label' => 'Shows',
            //   'table' => 'shows',
            //   'driver' => 'posts',
            //   'params' => array('post_type' => 'show')
            // ),
            // array(
            //   'type' => 'dropdown',
            //   'key' => 'post_parent',
            //   'label' => 'Member',
            //   'options' => array(array('id' => '', 'name' => '–')),
            //   'driver' => 'posts',
            //   'params' => array('post_type' => 'member'),
            //   'default' => array('getParam', 'parent')
            // ),
            // array(
            //   'type' => 'dropdown',
            //   'key' => 'image-type',
            //   'label' => 'Image Type',
            //   'options' => array(array('id' => '0', 'name' => '–')),
            //   'driver' => 'taxonomy',
            //   'params' => array('taxonomy' => 'image-type')
            // )


          )
        )
      ),
      'controls' => array(
        'children' => array(
          'save',
          'add',
          'delete',
          // array(
          //   'type' => 'text',
          //   'content' => array('request', 'getNotice')
          // ),
          'separator',
          'undo',
          'redo',
          'insert'
        )
      )



      // 'filters' => array(
      //   'type' => 'group',
      //   'display' => 'flex',
      //   'children' => array(
      //
      //     array(
      //       'label' => 'Image Type',
      //       'type' => 'dropdown',
      //       'key' => 'image-type',
      //       'options' => array(array('id' => '', 'name' => '–')),
      //       'driver' => 'taxonomy',
      //       'params' => array('taxonomy' => 'image-type')
      //     ),
      //     array(
      //       'label' => 'Member',
      //       'type' => 'dropdown',
      //       'key' => 'post_parent',
      //       'options' => array(array('id' => '', 'name' => '–')),
      //       'driver' => 'posts',
      //       'params' => array('post_type' => 'member')
      //     ),
      //     array(
      //       'type' => 'input',
      //       'label' => 'Recherche',
      //       'key' => 'search',
      //       'style' => 'flex:1',
      //       'width' => '20em'
      //     )
      //   )
      // )


    ));


  }


  /** DEPRECATED -> MOVED IN HOME.PHP
	 *	@hook 'actwall_home_header'
	 */
	public function print_product_body() {
    global $post, $wpdb;

    require_once get_stylesheet_directory() . '/class-image.php';

    $scale = 0.4;

    $member = get_post($post->post_parent);
    $title = get_the_title($post->ID);
    $member_name = get_the_title($post->post_parent);
    $price = get_post_meta($post->ID, 'price', true);
    $edition_total = intval(get_post_meta($post->ID, 'edition_total', true));
    $edition_sold = intval(get_post_meta($post->ID, 'edition_sold', true));
    $edition_num = $edition_total - $edition_sold;

    $attachments = get_post_meta($post->ID, 'attachments');

    if ($attachments) {

      Karma_Image::cache_images($attachments);

      $image = Karma_Image::get_image_source($attachments[0]);

      $src = $image['src'];
      $sizes = $image['sizes'];

      $photo_width = get_post_meta($post->ID, 'size-width', true);
      // $photo_height = get_post_meta($post->ID, 'size-height', true);

      if (!$photo_width) {

        $photo_width = 80;

      }

      $photo_height = $photo_width*$image['height']/$image['width'];

      $scale = $scale*80/$photo_height;

      $width = intval($photo_width)*$scale;
      $height = intval($photo_height)*$scale;

      $padding_top = get_post_meta($post->ID, 'padding-top', true);
      $padding_right = get_post_meta($post->ID, 'padding-right', true);
      $padding_bottom = get_post_meta($post->ID, 'padding-bottom', true);
      $padding_left = get_post_meta($post->ID, 'padding-left', true);

      if (!$padding_top) {

        $padding_top = 0;

      }

      if (!$padding_right) {

        $padding_right = $padding_top;

      }

      if (!$padding_bottom) {

        $padding_bottom = $padding_top;

      }

      if (!$padding_left) {

        $padding_left = $padding_right;

      }

      $padding_top = $padding_top*$scale;
      $padding_right = $padding_right*$scale;
      $padding_bottom = $padding_bottom*$scale;
      $padding_left = $padding_left*$scale;



      $border_width = 0;
      $border_image = 'none';
      $cadre = false;

      $cadre_id = get_post_meta($post->ID, 'cadre_id', true);

      if ($cadre_id) {

        $cadre_id = intval($cadre_id);

        $cadre = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cadres WHERE trash = 0 AND id = $cadre_id");

      }

      // if (!$cadre) { // -> use any!
      //
      //   $cadre = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cadres WHERE trash = 0 LIMIT 1");
      //
      // }

      if ($cadre && isset($cadre->textures) && $cadre->textures) {

        $textures = json_decode($cadre->textures);
        $texture = $textures[0];
        $cadre_width = floatval($cadre->width);
        $texture_width = floatval($texture->width);
        // $texture_img_src = wp_get_attachment_url($texture->image[0]);
        $texture_img = Karma_Image::get_image_source($texture->image[0]);
        $texture_img_width = $texture_img['width'];

        $border_slice = 0.95*$cadre_width*intval($texture_img_width)/$texture_width;

        $border_width = floatval($cadre_width*$scale);
        $border_img = $texture_img['src'];

      }


      if (count($attachments) > 1) {

        $other_images = array_map(function($id) {
          return Karma_Image::get_image_source($id);
        }, array_slice($attachments, 1));

      }

      include get_Stylesheet_directory() . '/include/members/single-header.php';
      include get_Stylesheet_directory() . '/include/product/single.php';

    } else {

      echo 'Image not found!';

    }

  }

  /**
	 *	@filter 'attachment_link'
	 */
	// public function attachment_link($link, $post_id) {
  //
  //   $post = get_post($post_id);
  //
  //   return home_url().'/attachment/'.$post->post_name;
  //
  // }




}


new Actwall_Products;

<?php




class Actwall_Series {

  /**
   * constructor
   */
  public function __construct() {

    // add_action('actwall_photo_header', array($this, 'print_photo_header'));
    // add_action('actwall_photo_body', array($this, 'print_photo_body'));

    add_action('init', array($this, 'register'));

    // if (is_admin()) {
    //
		// 	add_action('admin_menu', array($this, 'admin_menu'));
    //
		// }

    // add_action('rest_api_init', array($this, 'rest_api_init'));
    add_action('karma_fields_init', array($this, 'karma_fields_init'));



    // add_filter('attachment_link', array($this, 'attachment_link'), 10, 2);


    add_filter('karma_fields_posts_driver_add', array($this, 'karma_fields_post_add_product'), 10, 2);



  }







  /**
	 *	@filter 'karma_fields_posts_driver_add'
	 */
  public function karma_fields_post_add_product($args, $data) {

    if (isset($data['post_type'][0], $data['serie_id'][0]) && $data['post_type'][0] === 'product' && empty($args['post_title'][0])) {

      $serie = get_post($data['serie_id'][0]);

      if ($serie) {

        $args['post_title'] = $serie->post_title;

      }

    }

    return $args;
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
	 * @hook init
	 */
	public function register() {

    register_post_type('serie', array(
      'labels'             => array(
        'name' => 'Series',
        'singular_name' => 'Serie'
      ),
      'public'             => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'rewrite' => false,
      // 'rewrite'            => array(
      //   'slug' => 'member'
      // ),
      'capability_type'    => 'post',
      'has_archive'        => false,
      'hierarchical'       => true,
      // 'menu_position'      => null,
      'supports'           => array('title', 'editor'),
      'show_in_rest' => false
      // 'taxonomies' => array('module-category')
    ));


  }



  /**
   * @hook karma_fields_init
   */
  public function karma_fields_init($karma_fields) {

    $karma_fields->register_menu_item('series', 'edit.php?post_type=serie');

		$karma_fields->register_table('series', array(

			'header' => array(
				'title' => 'séries'
			),

			// 'modal' => array(
			// 	'width' => '25em'
			// ),
			'body' => array(
				'type' => 'grid',
				'driver' => 'posts',
				'params' => array(
          'post_type' => 'serie',
          'post_status' => 'publish',
					'ppp' => 100
				),
        'defaults' => array(
          'post_type' => 'serie',
          'post_status' => 'publish',
					'ppp' => 100
				),
        'children' => array(
          // array(
          //   'type' => 'index'
          // ),
          array(
            'type' => 'links',
            'label' => 'Name',
            'table' => 'serie',
            'params' => array('id' => array('getValue', 'id')),
            'content' => array('getValue', 'post_title')
          ),
          array(
            'type' => 'text',
            'label' => 'Member',
            'content' => array('queryValue', 'posts', array('getValue', 'post_parent'), 'post_title')
          )
        ),
				'modal' => array(
          'width' => '40em',
					'children' => array(
						array(
							'type' => 'input',
							'label' => 'Title',
							'key' => 'post_title'
						),
						// array(
						// 	'type' => 'textarea',
						// 	'label' => 'Description',
						// 	'key' => 'post_content'
						// ),
            array(
							'type' => 'dropdown',
              'options' => array(array('id' => '', 'name' => '-')),
							'label' => 'Member',
							'driver' => 'posts',
              'key' => 'post_parent',
              'params' => array('post_type' => 'member')
						)
					)
				)
			),
			// 'controls' => array(
			// 	'children' => array(
			// 		'save',
			// 		'upload',
			// 		'delete',
			// 		'undo',
			// 		'redo',
			// 		'insert'
			// 	)
			// ),



			'filters' => array(
				'type' => 'group',
				'display' => 'flex',
				'children' => array(
          array(
						'label' => 'Member',
						'type' => 'dropdown',
						'key' => 'post_parent',
						'options' => array(array('id' => '', 'name' => '–')),
            'driver' => 'posts',
            'params' => array('post_type' => 'member')
					),
					array(
						'type' => 'input',
						'label' => 'Recherche',
						'key' => 'search',
						'style' => 'flex:1'
					)
				)
			)
		));



    $karma_fields->register_table('serie', array(

      'type' => 'form',
      'driver' => 'posts',

      'header' => array(
        'type' => 'header',
				// 'title' => array('queryValue', 'posts', array('getParam', 'id'), 'post_title'),
        'title' => 'Test',
        'children' => array(
          'title',
          'close'
        )
			),
			'body' => array(
        // 'id' => array('getParam', 'id'),
        'type' => 'single',
        'children' => array(


          array(
            'type' => 'group',
            'display' => 'flex',
            'children' => array(
              array(
                'type' => 'input',
                'key' => 'name',
                'label' => 'Nom',
                'style' => 'flex-grow:1'
              ),
              array(
                'label' => 'ID',
                'type' => 'text',
                'content' => array('getValue', 'id')
              )
            )
          )
          // array(
          //   'type' => 'tinymce',
          //   'key' => 'post_content',
          //   'label' => 'Description',
          //   'header' => array(
          //     'children' => array('bold', 'italic')
          //   ),
          //   'translatable' => true
          // ),
          // array(
          //   'type' => 'group',
          //   // 'width' => '30em',
          //   'display' => 'flex',
          //   'children' => array(
          //
          //     array(
          //       'type' => 'input',
          //       // 'key' => 'year',
          //       'key' => 'date',
          //       'label' => 'Année (pour l’ordre)',
          //       'width' => 'auto'
          //     ),
          //     array(
          //       'type' => 'input',
          //       'key' => 'nice_date',
          //       'label' => 'Date (à afficher)',
          //       'width' => 'auto',
          //       'placeholder' => array('getValue', 'date')
          //     ),
          //     array(
          //       'type' => 'input',
          //       'key' => 'lieu',
          //       'label' => 'Lieu',
          //       'width' => 'auto'
          //     ),
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'mode',
          //       'label' => 'Mode',
          //       'options' => array(
          //         array('id' => '', 'name' => 'Tout format confondu'),
          //         array('id' => 'format', 'name' => 'Par format')
          //       ),
          //       'width' => 'auto'
          //     ),
          //     array(
          //       'type' => 'input',
          //       'key' => 'nb_tfc',
          //       'label' => 'Nombre',
          //       'enabled' => array('=', array('getValue', 'mode'), '')
          //     ),
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'type',
          //       'label' => 'Type',
          //       'options' => array(
          //         array('id' => '', 'name' => '-'),
          //         array('id' => 'type1', 'name' => 'Type 1'),
          //         array('id' => 'type2', 'name' => 'Type 2'),
          //         array('id' => 'type3', 'name' => 'Type 3')
          //       ),
          //       'width' => 'auto'
          //     )
          //   )
          // ),
          // array(
          //   'type' => 'array',
          //   'key' => 'selected_exhibitions',
          //   // 'translatable' => true,
          //   'label' => 'Selected exhibitions',
          //   'children' => array(
          //     array(
          //       'type' => 'input',
          //       'key' => 'name',
          //       'label' => 'Année',
          //       'width' => '10em'
          //     ),
          //     array(
          //       'type' => 'input',
          //       'key' => 'description',
          //       'label' => 'Nom'
          //     )
          //   )
          // ),
          // array(
          //   'type' => 'array',
          //   'key' => 'acquired',
          //   // 'translatable' => true,
          //   'label' => 'Acquired by institution',
          //   'children' => array(
          //     array(
          //       'type' => 'input',
          //       'key' => 'name',
          //       'label' => 'Année',
          //       'width' => '10em'
          //     ),
          //     array(
          //       'type' => 'input',
          //       'key' => 'description',
          //       'label' => 'Nom'
          //     )
          //   )
          // ),
          // array(
          //   'type' => 'textarea',
          //   'key' => 'keywords',
          //   'label' => 'Mots-clés',
          //   'translatable' => true
          // )
          // array(
          //   'label' => 'Formats',
          //   'type' => 'gridField',
          //   'driver' => 'formats',
          //   'params' => array(
          //     'serie_id' => array('getValue', 'id')
          //   ),
          //   'defaults' => array(
          //     'serie_id' => array('getValue', 'id')
          //   ),
          //   'position' => array('count', array('request', 'getQuery')),
          //   'children' => array(
          //     array(
          //       'type' => 'input',
          //       'key' => 'name',
          //       'label' => 'Nom',
          //       'width' => '1fr'
          //     ),
          //     array(
          //       'type' => 'input',
          //       'key' => 'nb',
          //       'label' => 'Nombre',
          //       'width' => '6em',
          //       'enabled' => array('=', array('queryValue', 'posts', array('getValue', 'serie_id'), 'mode'), 'format')
          //     ),
          //     array(
          //       'type' => 'input',
          //       'key' => 'image_width',
          //       'label' => 'Largeur image',
          //       'width' => '6em'
          //     ),
          //     array(
          //       'type' => 'input',
          //       'key' => 'image_height',
          //       'label' => 'Hauteur image',
          //       'width' => '6em'
          //     ),
          //     array(
          //       'type' => 'input',
          //       'key' => 'paper_width',
          //       'label' => 'Largeur papier',
          //       'width' => '6em'
          //     ),
          //     array(
          //       'type' => 'input',
          //       'key' => 'paper_height',
          //       'label' => 'Hauteur papier',
          //       'width' => '6em'
          //     ),
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'signature_id',
          //       'label' => 'Signature',
          //       'options' => array(
          //         array('id' => '', 'name' => '-')
          //       ),
          //       'driver' => 'cadre_signatures',
          //       'width' => '7em'
          //     ),
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'certificate_id',
          //       'label' => 'Certificat d’authenticité',
          //       'options' => array(
          //         array('id' => '', 'name' => '-')
          //       ),
          //       'driver' => 'cadre_certificates',
          //       'width' => '7em'
          //     ),
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'papier_id',
          //       'label' => 'Papier',
          //       'options' => array(
          //         array('id' => '', 'name' => '-')
          //       ),
          //       'driver' => 'cadre_papiers',
          //       'width' => '7em'
          //     ),
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'tirage_id',
          //       'label' => 'Technique de tirage',
          //       'options' => array(
          //         array('id' => '', 'name' => '-')
          //       ),
          //       'driver' => 'cadre_tirages',
          //       'width' => '7em'
          //     ),
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'verre_id',
          //       'label' => 'Verres',
          //       'options' => array(
          //         array('id' => '', 'name' => '-')
          //       ),
          //       'driver' => 'cadre_verres',
          //       'width' => '7em'
          //     ),
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'style_id',
          //       'label' => 'Style',
          //       'options' => array(
          //         array('id' => '', 'name' => '-')
          //       ),
          //       'driver' => 'cadre_styles',
          //       'width' => '7em'
          //     ),
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'collage_id',
          //       'label' => 'Collage',
          //       'options' => array(
          //         array('id' => '', 'name' => '-')
          //       ),
          //       'driver' => 'cadre_collages',
          //       'width' => '7em'
          //     ),
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'type_id',
          //       'label' => 'Type de cadre',
          //       'options' => array(
          //         array('id' => '', 'name' => '-')
          //       ),
          //       'driver' => 'cadre_types',
          //       'width' => '7em'
          //     ),
          //
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'essence_id',
          //       'label' => 'Essence',
          //       'options' => array(
          //         array('id' => '', 'name' => '-')
          //       ),
          //       'driver' => 'cadre_essences',
          //       'width' => '7em'
          //     ),
          //
          //     array(
          //       'type' => 'dropdown',
          //       'key' => 'cadre_id',
          //       'label' => 'Cadre',
          //       'options' => array(
          //         array('id' => '0', 'name' => 'Sans Cadre'),
          //       ),
          //       'driver' => 'cadres',
          //       'width' => '10em'
          //     ),
          //
          //     array(
          //       'type' => 'input',
          //       'key' => 'prix_tirage',
          //       'label' => 'Prix tirage',
          //       'width' => '6em'
          //     ),
          //     array(
          //       'type' => 'input',
          //       'key' => 'prix_encadre',
          //       'label' => 'Prix encadré',
          //       'width' => '6em'
          //     ),
          //     array(
          //       'label' => 'ID',
          //       'type' => 'text',
          //       'content' => array('getValue', 'id'),
          //       'width' => '3em'
          //     )
          //   )
          // ),



          // products
          // array(
          //   'label' => 'Images',
          //   'type' => 'gridField',
          //   'driver' => 'posts',
          //   'params' => array(
          //     'post_type' => 'product',
          //     'post_parent' => array('getValue', 'post_parent'),
          //     'post_status' => 'publish',
          //     'serie_id' => array('getValue', 'id')
          //   ),
          //   'defaults' => array(
          //     'post_type' => 'product',
          //     'post_parent' => array('getValue', 'post_parent'),
          //     'post_status' => 'publish',
          //     'serie_id' => array('getValue', 'id')
          //   ),
          //   'position' => array('count', array('request', 'getQuery')),
          //   'children' => array(
          //     array(
          //       'type' => 'group',
          //       'width' => 'auto',
          //       'children' => array(
          //         array(
          //           'type' => 'file',
          //           'key' => 'attachments',
          //           'max' => 1,
          //           'label' => 'File',
          //           // 'uploader' => 'wp',
          //           'width' => 'auto',
          //           'params' => array('parent' => array('getValue', 'post_parent'))
          //         ),
          //         array(
          //           'label' => 'sens',
          //           'type' => 'text',
          //           'content' => array('?',
          //             array(
          //               '<',
          //               array('queryValue', 'medias', array('getValue', 'attachments'), 'width'),
          //               array('queryValue', 'medias', array('getValue', 'attachments'), 'height')
          //             ),
          //             'portait',
          //             'panorama'
          //           )
          //         ),
          //         array(
          //           'type' => 'dropdown',
          //           'key' => 'post_status',
          //           'label' => 'Status',
          //           'options' => array(array('id' => 'draft', 'name' => 'Draft'), array('id' => 'publish', 'name' => 'Publish'))
          //         ),
          //         array(
          //           'type' => 'text',
          //           'content' => array('getValue', 'id')
          //         )
          //       )
          //     ),
          //
          //     array(
          //       'type' => 'group',
          //       'width' => '1fr',
          //       'children' => array(
          //
          //         array(
          //           'type' => 'input',
          //           'key' => 'name',
          //           'label' => 'Titre',
          //           'default' => array('queryValue', 'posts', array('queryValue', 'posts', array('getValue', 'id'), 'serie_id'), 'post_title'),
          //           'placeholder' => array('queryValue', 'posts', array('queryValue', 'posts', array('getValue', 'id'), 'serie_id'), 'post_title')
          //           // 'width' => '18em'
          //         ),
          //         array(
          //           'type' => 'tinymce',
          //           'key' => 'post_content',
          //           'label' => 'Description',
          //           'translatable' => true,
          //           'header' => array(
          //             'children' => array('bold', 'italic')
          //           )
          //         ),
          //         array(
          //           'type' => 'textarea',
          //           'key' => 'keywords',
          //           'label' => 'Mots-clés',
          //           'translatable' => true
          //         )
          //       )
          //     ),
          //
          //     array(
          //       'type' => 'group',
          //       'width' => 'auto',
          //       'children' => array(
          //         array(
          //           'key' => 'nb_tfc',
          //           'type' => 'input',
          //           'label' => 'Nombre',
          //           'width' => '8em',
          //           'placeholder' => array('queryValue', 'posts', array('queryValue', 'posts', array('getValue', 'id'), 'serie_id'), 'nb_tfc'),
          //           'disabled' => array("=", array('queryValue', 'posts', array('queryValue', 'posts', array('getValue', 'id'), 'serie_id'), 'mode'), "format")
          //         ),
          //
          //         array(
          //           // 'label' => '_total_produit',
          //           'type' => 'hidden',
          //           'key' => '_total_produit',
          //           'width' => '6em',
          //           'value' => array('sum', array('getValue', 'nb_produit'))
          //         ),
          //         array(
          //           // 'label' => '_min_price',
          //           'type' => 'hidden',
          //           'key' => '_min_price',
          //           'width' => '6em',
          //           'value' => array('min', array('...', array('getValue', '_prix_encadre'), array('getValue', '_prix_tirage')))
          //         ),
          //         array(
          //           // 'label' => '_max_price',
          //           'type' => 'hidden',
          //           'key' => '_max_price',
          //           'width' => '6em',
          //           'value' => array('max', array('getValue', '_prix_encadre'))
          //         ),
          //         array(
          //           'label' => '_min_price',
          //           'type' => 'text',
          //           'content' => array('getValue', '_min_price')
          //         ),
          //         array(
          //           'label' => '_max_price',
          //           'type' => 'text',
          //           'content' => array('getValue', '_max_price')
          //         )
          //       )
          //     ),
          //
          //
          //     // products editions array
          //     array(
          //       'type' => 'array',
          //
          //       'footer' => array(
          //         'children' => array(
          //           array(
          //             'type' => 'add',
          //             'text' => 'Add edition'
          //           )
          //         )
          //       ),
          //       'children' => array(
          //
          //         array(
          //           'type' => 'group',
          //           'width' => '12em',
          //           'children' => array(
          //             array(
          //               'type' => 'dropdown',
          //               'label' => 'Format',
          //               'key' => 'format_id',
          //               'options' => array(array('id' => '', 'name' => '-')),
          //               'driver' => 'formats',
          //               'params' => array(
          //                 'serie_id' => array('queryValue', 'posts', array('request', 'getId'), 'serie_id')
          //               )
          //             ),
          //             array(
          //               'label' => 'Cadre',
          //               'type' => 'dropdown',
          //               'key' => 'with_cadre',
          //               'options' => array(
          //                 array('id' => '', 'name' => 'Avec ou sans'),
          //                 array('id' => '1', 'name' => 'Seulement avec'),
          //                 array('id' => '2', 'name' => 'Seulement sans')
          //               )
          //             )
          //           )
          //         ),
          //         array(
          //           'type' => 'group',
          //           'width' => '10em',
          //           'children' => array(
          //             array(
          //               'key' => 'nb',
          //               'type' => 'input',
          //               'label' => 'Nombre',
          //               'placeholder' => array('queryValue', 'formats', array('getValue', 'format_id'), 'nb'),
          //               'enabled' => array("=", array('queryValue', 'posts', array('queryValue', 'posts', array('request', 'getId'), 'serie_id'), 'mode'), "format")
          //             ),
          //             array(
          //               'key' => 'nb_produit',
          //               'type' => 'input',
          //               'label' => 'Nombre produits',
          //             ),
          //             array(
          //               'label' => 'Nombre vendu',
          //               'type' => 'links',
          //               'table' => 'ventes',
          //               'params' => array(
          //                 'product_id' => array('request', 'getId'),
          //                 'format_id' => array('getValue', 'format_id'),
          //                 'member_id' => array('queryValue', 'posts', array('request', 'getId'), 'post_parent'),
          //                 'serie_id' => array('queryValue', 'posts', array('request', 'getId'), 'serie_id'),
          //                 'prix' => array('||', array('getValue', 'prix_encadre'), array('queryValue', 'formats', array('getValue', 'format_id'), 'prix_encadre'))
          //               ),
          //               'content' => array('getLength', array('query', 'ventes', array('format_id' => array('getValue', 'format_id'), 'product_id' => array('request', 'getId'))))
          //             )
          //           )
          //         ),
          //         array(
          //           'type' => 'group',
          //           'width' => '10em',
          //           'children' => array(
          //             array(
          //               'key' => 'prix_tirage',
          //               'type' => 'input',
          //               'label' => 'Prix (tirage seul)',
          //               'placeholder' => array('queryValue', 'formats', array('getValue', 'format_id'), 'prix_tirage')
          //             ),
          //             array(
          //               'key' => 'prix_encadre',
          //               'type' => 'input',
          //               'label' => 'Prix (encadré)',
          //               'placeholder' => array('queryValue', 'formats', array('getValue', 'format_id'), 'prix_encadre')
          //             ),
          //             array(
          //               'key' => '_prix_tirage',
          //               'type' => 'hidden',
          //               'value' => array(
          //                 '||',
          //                 array('getValue', 'prix_tirage'),
          //                 array('queryValue', 'formats', array('getValue', 'format_id'), 'prix_tirage')
          //               )
          //             ),
          //             array(
          //               'key' => '_prix_encadre',
          //               'type' => 'hidden',
          //               // 'label' => '_prix_encadre',
          //               'value' => array(
          //                 '||',
          //                 array('getValue', 'prix_encadre'),
          //                 array('queryValue', 'formats', array('getValue', 'format_id'), 'prix_encadre')
          //               )
          //             )
          //           )
          //         ),
          //         array(
          //           'type' => 'group',
          //           'width' => '28em',
          //           'children' => array(
          //             array(
          //               'type' => 'textarea',
          //               'key' => 'framing',
          //               'label' => 'Framing',
          //               // 'placeholder' => array('getValue', '_framing'),
          //               'height' => '5em'
          //             ),
          //             array(
          //               'type' => 'textarea',
          //               'key' => 'specifications',
          //               'label' => 'Specifications',
          //               // 'placeholder' => array('getValue', '_specifications'),
          //               'height' => '5em'
          //             )
          //           )
          //         )
          //       )
          //     )
          //   )
          // )
        )
			),
      'footer' => array(
        'translatable' => true,
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


new Actwall_Series;

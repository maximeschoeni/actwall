<?php


Class Actwall_Driver_Formats {

  /**
	 * update
	 */
  public function update($item, $id) {
    global $wpdb;

    if (!is_user_logged_in()) {
      return;
    }

    $table = "{$wpdb->prefix}formats";

    $values = array();
    $value_types = array();

    foreach ($item as $key => $value) {

      switch ($key) {

        case 'name':
          $values[$key] = $value[0];
          $value_types[] = '%s';
          break;

        case 'member_id':
        case 'style_id':
        case 'collage_id':
        case 'type_id':
        case 'essence_id':
        case 'verre_id':
        case 'tirage_id':
        case 'papier_id':
        case 'cadre_id':
        case 'serie_id':
        case 'signature_id':
        case 'certificate_id':
        case 'nb':
          $values[$key] = intval($value[0]);
          $value_types[] = '%d';
          break;

        case 'image_width':
        case 'image_height':
        case 'paper_width':
        case 'paper_height':
        case 'prix_tirage':
        case 'prix_encadre':
          $values[$key] = floatval($value[0]);
          $value_types[] = '%s';
          break;

        // case 'paper_width':
        //   $paper_width = (float) $value[0];
        //   if (!$paper_width) {
        //     if (isset($item['image_width'][0])) {
        //       $paper_width = (float) $item['image_width'][0];
        //     } else {
        //       $id = (int) $id;
        //       $paper_width = (float) get_var("SELECT image_width FROM {$wpdb->prefix}formats WHERE id = $id");
        //     }
        //   }
        //   $values['paper_width'] = $paper_width;
        //   $value_types[] = '%s';
        //   break;
        //
        // case 'paper_height':
        //   $paper_height = (float) $value[0];
        //   if (!$paper_height) {
        //     if (isset($item['image_width'][0])) {
        //       $paper_height = (float) $item['image_height'][0];
        //     } else {
        //       $id = (int) $id;
        //       $paper_height = (float) get_var("SELECT image_height FROM {$wpdb->prefix}formats WHERE id = $id");
        //     }
        //   }
        //   $values['paper_height'] = $paper_height;
        //   $value_types[] = '%s';
        //   break;

        case 'trash':
          $values[$key] = $value[0];
          $value_types[] = '%d';
          break;

      }

    }

    if ($values && $value_types) {

      $wpdb->update($table, $values, array('id' => $id), $value_types, array('%d'));

    }

    return true;

  }


  /**
	 * add
	 */
  public function add() {
    global $wpdb;

    $table = "{$wpdb->prefix}formats";

    $wpdb->insert($table, array('trash' => 1), array('%d'));

    return $wpdb->insert_id;

  }

	/**
	 * query
	 */
  public function query($params) {
    global $wpdb;

    $table = "{$wpdb->prefix}formats";

    // order

    $order = '';

    if (isset($params['orderby'])) {

      $dir = isset($params['order']) && $params['order'] === 'desc' ? 'DESC' : 'ASC';

      switch ($params['orderby']) {

        case 'name':
        default:
          $order = "ORDER BY name $dir";
          break;

      }

    }


    // limit

    $limit = '';

    if (isset($params['ppp'])) {

      $ppp = intval($params['ppp']);

      if ($ppp > 0) {

        if (isset($params['page'])) {

          $page = intval($params['page']);

        } else {

          $page = 1;

        }

        $offset = $ppp*($page-1);

        $limit = "LIMIT $offset, $ppp";

      }

    }


    // where

    $where_clauses = array();

    $where_clauses[] = "trash = 0";

    // if (isset($params['search'])) {
    //
    //   $search = esc_sql($params['search']);
    //   $where_clauses[] = "(name LIKE '%$search%' OR content LIKE '%$search%')";
    //
    // }

    if (isset($params['member_id'])) {

      $member_id = intval($params['member_id']);
      $where_clauses[] = "member_id = $member_id";

    }

    if (isset($params['serie_id'])) {

      $serie_id = intval($params['serie_id']);
      $where_clauses[] = "serie_id = $serie_id";

    }

    if (isset($params['ids']) ) {

      $ids = explode(',', $params['ids']);

      if ($ids) {

        $ids = array_map('intval', $ids);
        $ids = implode(',', $ids);

        $where_clauses[] = "id IN ($ids)";

      }

    }

    if ($where_clauses) {

      $where = "WHERE " .implode(' AND ', $where_clauses);

    } else {

      $where = '';
    }

    $sql = "SELECT
      id,
      name,
      member_id,
      serie_id,
      image_width,
      image_height,
      paper_width,
      paper_height,
      style_id,
      collage_id,
      type_id,
      essence_id,
      verre_id,
      tirage_id,
      papier_id,
      cadre_id,
      signature_id,
      certificate_id,
      prix_tirage,
      prix_encadre,
      nb

      FROM $table
      $where
      $order
      $limit";

    return $wpdb->get_results($sql);

  }

  /**
	 * count
	 */
  public function count($params) {
    global $wpdb;

    return 0;

    // $table = "{$wpdb->prefix}ventes";
    //
    // // where
    //
    // $where_clauses = array();
    //
    // $where_clauses[] = "trash = 0";
    //
    // // if (isset($params['search'])) {
    // //
    // //   $search = esc_sql($params['search']);
    // //   $where_clauses[] = "(name LIKE '%$search%' OR content LIKE '%$search%')";
    // //
    // // }
    //
    // if (isset($params['member_id'])) {
    //
    //   $member_id = intval($params['member_id']);
    //   $where_clauses[] = "member_id = $member_id";
    //
    // }
    //
    // if (isset($params['ids']) ) {
    //
    //   $ids = explode(',', $params['ids']);
    //
    //   if ($ids) {
    //
    //     $ids = array_map('intval', $ids);
    //     $ids = implode(',', $ids);
    //
    //     $where_clauses[] = "id IN ($ids)";
    //
    //   }
    //
    // }
    //
    // if ($where_clauses) {
    //
    //   $where = "WHERE " .implode(' AND ', $where_clauses);
    //
    // } else {
    //
    //   $where = '';
    // }
    //
    // return $wpdb->get_var("SELECT COUNT(id) FROM $table $where");

  }




}

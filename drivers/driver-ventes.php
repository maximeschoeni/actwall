<?php


Class Actwall_Driver_Ventes {

  /**
	 * update
	 */
  public function update($item, $id) {
    global $wpdb;

    if (!is_user_logged_in()) {
      return;
    }

    $table = "{$wpdb->prefix}ventes";

    $values = array();
    $value_types = array();

    foreach ($item as $key => $value) {

      switch ($key) {

        // case 'name':
        //   $values[$key] = $value[0];
        //   $value_types[] = '%s';
        //   break;

        case 'member_id':
        case 'product_id':
        case 'format_id':
        case 'produit':
          $values[$key] = intval($value[0]);
          $value_types[] = '%d';
          break;

        case 'prix':
          $values[$key] = floatval($value[0]);
          $value_types[] = '%s';
          break;

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

    $table = "{$wpdb->prefix}ventes";

    $wpdb->insert($table, array('trash' => 1), array('%d'));

    return $wpdb->insert_id;

  }

	/**
	 * query
	 */
  public function query($params) {
    global $wpdb;

    $table = "{$wpdb->prefix}ventes";

    // order

    $order = '';

    if (isset($params['orderby'])) {

      $dir = isset($params['order']) && $params['order'] === 'asc' ? 'ASC' : 'DESC';

      switch ($params['orderby']) {

        case 'name':
        default:
          $order = "ORDER BY time $dir";
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

      // $serie_id = intval($params['serie_id']);
      // $where_clauses[] = "serie_id = $serie_id";

    }

    if (isset($params['product_id'])) {

      $product_id = intval($params['product_id']);
      $where_clauses[] = "product_id = $product_id";

    }

    if (isset($params['format_id'])) {

      $format_id = intval($params['format_id']);
      $where_clauses[] = "format_id = $format_id";

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
      `time`,
      member_id,
      product_id,
      format_id,
      produit,
      prix
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

    $table = "{$wpdb->prefix}ventes";

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

      // $serie_id = intval($params['serie_id']);
      // $where_clauses[] = "serie_id = $serie_id";

    }

    if (isset($params['product_id'])) {

      $product_id = intval($params['product_id']);
      $where_clauses[] = "product_id = $product_id";

    }

    if (isset($params['format_id'])) {

      $format_id = intval($params['format_id']);
      $where_clauses[] = "format_id = $format_id";

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

    return $wpdb->get_var("SELECT COUNT(id) FROM $table $where");

  }




}

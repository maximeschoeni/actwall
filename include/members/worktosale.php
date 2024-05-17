<header id="header" class="main-header">
  <?php include get_stylesheet_directory().'/include/header.php'; ?>
</header>
<header class="main-header placeholder">
  <?php include get_stylesheet_directory().'/include/header.php'; ?>
</header>
<main class="single worktosale">

  <?php /*
  <header class="header-filter" id="header-filter">
    <div class="filters-button" id="button-filters-container">
      <button id="button-filters">
        <div class="picto plus">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <g>
              <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
            </g>
          </svg>
        </div>
        <span>open filters</span>
      </button>
    </div>
    <div id="header-categories-container" class="header-categories-container">
      <?php if ($image_types && !is_wp_error($image_types)) { ?>
        <ul class="categories" id="image-types">
          <?php foreach ($image_types as $image_type) { ?>
            <li><a href="#" onclick="Actwall.Home.setFilter('image-type', <?php echo $image_type->term_id; ?>); return false" class="active" id="image-type-<?php echo $image_type->term_id; ?>"><span><?php echo $image_type->name; ?></span></a></li>
          <?php } ?>
        </ul>
      <?php } ?>
    </div>
  </header>
  */ ?>

  <?php if ($photos) { ?>
    <div id="photo-container">

      <?php include get_stylesheet_directory().'/include/members/single-header.php' ?>
      <div class="filters" id="filters"></div>
      <?php
      // var_dump($photos);

      ?>
      <?php include get_stylesheet_directory().'/include/home/grid.php'; ?>

      <?php /*
      <ul class="grid">
        <?php foreach ($photos as $photo) { ?>

          <li style="margin:<?php echo $photo['margin']['top']; ?>em <?php echo $photo['margin']['right']; ?>em <?php echo $photo['margin']['bottom']; ?>em <?php echo $photo['margin']['left']; ?>em">
            <a href="<?php echo $photo['permalink']; ?>">
              <figure>
                <img
                <?php if (empty($photo['has_frame'])) { ?>
                  class="no-frame"
                <?php } ?>
                  src="<?php echo $photo['src']; ?>"
                  srcset="<?php echo implode(',', array_map(function($size) {
                    return "{$size['src']} {$size['width']}w";
                  }, $photo['sizes'])); ?>"
                  sizes="(min-width: 1024px) 1024px, 100vw"
                  style="
                    width:<?php echo $photo['width']; ?>em;
                    height:auto;
                    <?php if (isset($photo['border_width'], $photo['border_img'], $photo['border_slice'])) { ?>
                      border-style: solid;
                      border-width:<?php echo $photo['border_width']; ?>em;
                      border-image: url(<?php echo $photo['border_img']; ?>) <?php echo $photo['border_slice']; ?> stretch;
                    <?php } ?>

                    padding:<?php echo $photo['padding']['top']; ?>em <?php echo $photo['padding']['right']; ?>em <?php echo $photo['padding']['bottom']; ?>em <?php echo $photo['padding']['left']; ?>em;
                    "
                >
                <figcaption class="caption">
                  <h3><?php echo $photo['member_name']; ?>, <?php echo $photo['title']; ?></h3>
                  <div class="price">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18">
                      <path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="black"/>
                    </svg>
                    A partir de <?php echo get_post_meta($post->ID, 'price', true); ?>.-
                  </div>
                </figcaption>
              </figure>
            </a>

          </li>
        <?php } ?>
      </ul>
      */ ?>

    </div>
    <!-- <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/template-filters.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/template-home.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/template-worktosale.js"></script> -->
    <script>
      {
        const header = document.getElementById("header");
        const headerTop = document.getElementById("header-top");
        const buttonFilter = document.getElementById("button-filters");
        const containerFilters = document.getElementById("filters");
        const headerFilter = document.getElementById("header-filter");
        const headerBottom = document.getElementById("header-bottom");

        const buttonFilterContainer = document.getElementById("button-filters-container");
        const photoContainer = document.getElementById("photo-container");
        const headerFilterContainer = document.getElementById("header-categories-container");

        // new Sticky(
        //   containerFilters,
        //   () => header.clientHeight + Math.max(0, headerFilter.clientHeight + containerFilters.clientHeight - window.innerHeight),
        //   () => header.clientHeight + headerFilter.clientHeight + headerBottom.clientHeight
        // );

        new Sticky2(
          headerBottom,
          null,
          () => header.getBoundingClientRect().height, //clientHeight,
          () => header.getBoundingClientRect().height //header.clientHeight
        );

        // new Sticky(
        //   headerFilter,
        //   () => header.clientHeight,
        //   () => header.clientHeight + headerBottom.clientHeight
        // );

        const home = new Actwall.Worktosale();

        // abduct(buttonFilterContainer, home.buildFiltersButton());
        // abduct(headerFilterContainer, home.buildHeaderTaxonomy("image-type"));


        // abduct(photoContainer, home.build());

      }




    </script>
  <?php } ?>
  <?php //include get_stylesheet_directory().'/include/contact.php'; ?>
</main>

<header class="header-bottom" id="header-bottom">
  <div class="filters-button" id="button-filters-container">
    <button id="button-filters">
      <div class="picto plus">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
          <g>
            <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
          </g>
        </svg>
      </div>
      <span>Filters</span>
    </button>
    <!-- <div id="filter-container"></div> -->

  </div>

  <?php if ($image_types && !is_wp_error($image_types)) { ?>
    <ul class="categories" id="image-types">
      <?php foreach ($image_types as $image_type) { ?>
        <li><a href="#" onclick="Actwall.Home.setFilter('image-type', <?php echo $image_type->term_id; ?>); return false" class="active" id="image-type-<?php echo $image_type->term_id; ?>"><span><?php echo $image_type->name; ?></span></a></li>
      <?php } ?>
    </ul>
  <?php } ?>
</header>

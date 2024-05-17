<section class="shows">
  <div class="headline"><h3><?php echo __('Exhibitions', 'actwall'); ?></h3></div>
  <ul class="relation photo-shows">
    <?php foreach ($show_query->posts as $show) { ?>
      <li>
        <a href="<?php echo get_permalink($show->ID); ?>">
          <?php
            $image_id = get_post_meta($show->ID, 'image', true);

            if ($image_id) {
              $image = Karma_Image::get_image_source($image_id);
            }

            $place = get_post_meta($show->ID, 'place', true);
            $city = get_post_meta($show->ID, 'city', true);

            $date_start = get_post_meta($show->ID, 'date_start', true);
            $date_end = get_post_meta($show->ID, 'date_end', true);

          ?>
          <figure>
            <?php if (isset($image)) { ?>
              <img
                src="<?php echo $image['src']; ?>"
                srcset="<?php echo implode(',', array_map(function($size) {
                  return "{$size['src']} {$size['width']}w";
                }, $image['sizes'])); ?>"
                sizes="(min-width: 900px) 40vw, 100vw"
              >
            <?php } ?>
          </figure>
          <div class="row">
            <div class="title">
              <h4><?php echo get_the_title($show->ID); ?></h4>
            </div>
            <div class="date">
              <span><?php do_action('actwall_daterange', $date_start, $date_end); ?></span>
            </div>
            <div class="place">
              <span><?php echo $place; ?></span>
            </div>
            <div class="city">
              <span><?php echo $city; ?></span>
            </div>
          </div>
        </a>
      </li>
    <?php } ?>
  </ul>
</section>

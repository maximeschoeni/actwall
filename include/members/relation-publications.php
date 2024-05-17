<section class="books">
  <div class="headline"><h3><?php echo __('Books', 'actwall'); ?></h3></div>
  <ul class="relation">
    <?php foreach ($publication_query->posts as $publication) { ?>
      <li>
        <?php
          $image_id = get_post_meta($publication->ID, 'image', true);

          if ($image_id) {
            $image = Karma_Image::get_image_source($image_id);
          }

          $publisher = get_post_meta($publication->ID, 'publisher', true);
          $city = get_post_meta($publication->ID, 'city', true);
          $date = get_post_meta($publication->ID, 'date', true);

        ?>
        <a href="<?php echo get_permalink($image_id); ?>">

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
              <h4><?php echo get_the_title($publication->ID); ?></h4>
            </div>
            <div class="date">
              <span><?php echo $date; //do_action('actwall_daterange', $date); ?></span>
            </div>

            <div class="publisher">
              <span><?php echo $publisher; ?></span>
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

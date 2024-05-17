<ul class="member-photos">
  <?php foreach ($photos as $photo) { ?>
    <li>
      <a href="<?php echo get_permalink($photo['product_id']); ?>" onclick="return Actwall.openProduct(<?php echo $photo['product_id']; ?>, '<?php echo $photo['permalink']; ?>');">
        <?php

          if (isset($photo['editions'])) {

            $edition = $photo['editions'][array_rand($photo['editions'])];
            include get_stylesheet_directory().'/include/product/image.php';

          }

        ?>
<?php /*
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
            data-title="<?php echo get_the_title($photo['id']); ?>"
            data-year="<?php echo substr(get_post_meta($photo['id'], 'date', true), 0, 4); ?>"
          >
        </figure>
*/ ?>
      </a>
    </li>
  <?php } ?>
</ul>

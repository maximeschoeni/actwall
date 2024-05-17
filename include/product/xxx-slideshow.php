
<div class="slideshow" id="slideshow">
  <div class="viewer">
    <div class="frame">
      <?php include get_stylesheet_directory().'/include/product/image.php'; ?>
    </div>

    <?php if (isset($other_images)) { ?>
      <?php foreach ($other_images as $other_image) { ?>
        <div class="frame">
          <figure>
            <img class="no-frame"
              src="<?php echo $other_image['src']; ?>"
              srcset="<?php echo implode(',', array_map(function($size) {
                return "{$size['src']} {$size['width']}w";
              }, $other_image['sizes'])); ?>"
              sizes="(min-width: 1024px) 1024px, 100vw"
            >
          </figure>
        </div>
      <?php } ?>
    <?php } ?>
  </div>
  <div class="controls slideshow-body">
    <div class="navigation left-navigation">
      <div class="arrow left-arrow">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="none">
          <path d="M3.33291 15L14 -9.53674e-07L10.9138 -6.83872e-07L9.53674e-07 15L3.33291 15Z" fill="black"></path>
          <path d="M3.33291 15L14 30L10.9138 30L9.53674e-07 15L3.33291 15Z" fill="black"></path>
        </svg>
      </div>
    </div>
    <div class="navigation right-navigation">
      <div class="arrow right-arrow">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="none">
          <path d="M10.6671 15L1.31134e-06 -9.53674e-07L3.08618 -6.83872e-07L14 15L10.6671 15Z" fill="black"></path>
          <path d="M10.6671 15L-9.49328e-08 30L3.08618 30L14 15L10.6671 15Z" fill="black"></path>
        </svg>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/product-slideshow.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/player-v5.js"></script>
<script>
  addEventListener("DOMContentLoaded", event => {
    const container = document.getElementById("slideshow");
    new Actwall.ProductSlideshow(container);
  });
</script>

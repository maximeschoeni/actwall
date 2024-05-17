<section class="photo-slideshow">
  <div class="frame">
    <figure>
      <img
        <?php if (empty($border_width)) { ?>
          class="no-frame"
        <?php } ?>
        src="<?php echo $src; ?>"
        srcset="<?php echo implode(',', array_map(function($size) {
          return "{$size['src']} {$size['width']}w";
        }, $sizes)); ?>"
        sizes="(min-width: 1024px) 1024px, 100vw"
        style="
          width:<?php echo $width; ?>em;
          height:auto;
          <?php if (isset($border_width, $border_img, $border_slice)) { ?>
            border-style: solid;
            border-width:<?php echo $border_width; ?>em;
            border-image: url(<?php echo $border_img; ?>) <?php echo $border_slice; ?> stretch;
          <?php } ?>
          padding:<?php echo $padding_top; ?>em <?php echo $padding_right; ?>em <?php echo $padding_bottom; ?>em <?php echo $padding_left; ?>em;
          "
      >
    </figure>
  </div>
</section>
<section class="photo-details">
  <div class="single-columns">
    <div class="mobile-only">
      <?php include get_stylesheet_directory().'/include/attachment/detail-navigation.php'; ?>
    </div>
    <div class="single-column">
      <!-- <div class="photo-title-container"> -->
        <h1><span><?php echo get_the_title($post->ID); ?></span></h1>
      <!-- </div> -->
      <!-- <div class="placeholder"></div> -->
      <div class="details">
        <?php if ($price) { ?>
          <div class="price"><span><?php echo $price; ?>.-</span></div>
        <?php } ?>
        <div class="edition-size">
          <?php if ($edition_total) { ?>
            <div class="edition">
              <!-- <div class="indicator" style="background-color:var(--<?php if ($edition_num > 0) echo 'green'; else echo 'red'; ?>);"></div> -->
              <span><?php echo __('Edition', 'actwall'); ?> <?php echo $edition_num; ?>/<?php echo $edition_total; ?></span>
            </div>
          <?php } ?>
          <?php if ($photo_width && $photo_height) { ?>
            <div class="size">
              <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
              </svg>
              <span>Size <?php echo intval($photo_width); ?> x <?php echo intval($photo_height); ?>CM (<?php echo intval($photo_width*0.393701); ?> x <?php echo intval($photo_height*0.393701); ?> in)</span>
            </div>
          <?php } ?>
        </div>
        <?php include get_stylesheet_directory().'/include/attachment/acquire-button.php'; ?>
        <?php
          $specifications = get_post_meta($post->ID, 'specifications', true);
          $framing = get_post_meta($post->ID, 'framing', true);
          $history = get_post_meta($post->ID, 'exhibitions-history', true);
          $shipping = get_post_meta($post->ID, 'shipping', true);

        ?>
        <ul class="info" id="photo-details-info">
          <?php if ($specifications) { ?>
            <li>
              <a class="info-handle">
                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                  <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
                </svg>
                <span><?php echo __('Specifications', 'actwall'); ?></span>
              </a>
              <div class="info-body">
                <div class="info-content"><?php echo nl2br($specifications); ?></div>
              </div>
            </li>
          <?php } ?>
          <?php if ($framing) { ?>
            <li>
              <a class="info-handle">
                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                  <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
                </svg>
                <span><?php echo __('Framing', 'actwall'); ?></span>
              </a>
              <div class="info-body">
                <div class="info-content"><?php echo nl2br($framing); ?></div>
              </div>
            </li>
          <?php } ?>
          <?php if ($history) { ?>
            <li>
              <a class="info-handle">
                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                  <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
                </svg>
                <span><?php echo __('Exhibitions history', 'actwall'); ?></span>
              </a>
              <div class="info-body">
                <div class="info-content"><?php echo nl2br($history); ?></div>
              </div>
            </li>
          <?php } ?>
          <?php if ($shipping) { ?>
            <li>
              <a class="info-handle">
                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                  <path d="M16.3867 7.58205L11.6774 12.2914L7.09536 7.70933L5.44073 9.36396L10.0228 13.946L11.8047 15.7279L13.4593 14.0733L18.1686 9.36396L16.3867 7.58205Z" fill="black"/>
                </svg>
                <span><?php echo __('Shipping', 'actwall'); ?></span>
              </a>
              <div class="info-body">
                <div class="info-content"><?php echo nl2br($shipping); ?></div>
              </div>
            </li>
          <?php } ?>
        </ul>
        <script>
          Accordeon.register(document.getElementById("photo-details-info"), "li", ".info-handle", ".info-body");
        </script>
      </div>
    </div>
    <div class="single-column">
      <div class="desktop-only">
        <?php include get_stylesheet_directory().'/include/attachment/detail-navigation.php'; ?>
      </div>
      <div class="about" id="about-work">
        <h2>
          <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
            <path d="M7.09471 14.4375L11.804 9.72815L16.3861 14.3102L18.0407 12.6556L13.4587 8.07352L11.6768 6.29161L10.0221 7.94624L5.3128 12.6556L7.09471 14.4375Z" fill="black"/>
          </svg>
          <!-- <span><?php echo __('About the work', 'actwall'); ?></span> -->
        </h2>
        <div class="slider"><div class="content"><?php echo get_the_excerpt($post); ?></div></div>
        <a class="more">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <rect width="15.48" height="15.66" fill="white"/>
            <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="#C9C8C8"/>
          </svg>
          <span><?php echo __('Explore portfolio', 'actwall'); ?></span>
        </a>
      </div>
      <div class="about" id="about-photographer">
        <h2>
          <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
            <path d="M7.09471 14.4375L11.804 9.72815L16.3861 14.3102L18.0407 12.6556L13.4587 8.07352L11.6768 6.29161L10.0221 7.94624L5.3128 12.6556L7.09471 14.4375Z" fill="black"/>
          </svg>
          <span><?php echo __('About the photographer', 'actwall'); ?></span>
        </h2>
        <div class="slider"><div class="content"><?php $member = get_post($post->post_parent); echo apply_filters('sublanguage_translate_post_field', $member->post_content, $member, 'post_content'); ?></div></div>

        <a class="more">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <rect width="15.48" height="15.66" fill="white"/>
            <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="#C9C8C8"/>
          </svg>
          <span><?php echo __('Explore biography', 'actwall'); ?></span>
        </a>
      </div>
      <script>
        new Accordeon(document.getElementById("about-work"), "h2", ".slider", null, true).init();
        new Accordeon(document.getElementById("about-photographer"), "h2", ".slider");
      </script>
    </div>
  </div>
</section>
<section class="other-works">
  <div class="headline"><h3><?php echo __('All works', 'actwall'); ?></h3></div>
  <?php do_action('actwall_member_photos', $post->post_parent); ?>
  <div class="more">
    <a href="<?php echo get_permalink($post->post_parent); ?>">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
        <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
      </svg>
      <span><?php echo __('See more', 'actwall'); ?></span>
    </a>
  </div>
</section>
<section class="shows">
  <div class="headline"><h3><?php echo __('Exhibitions', 'actwall'); ?></h3></div>
  <?php do_action('actwall_photo_shows', $post->ID); ?>
</section>

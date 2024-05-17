<header id="header" class="main-header">
  <?php include get_stylesheet_directory().'/include/header.php'; ?>
</header>
<header class="main-header placeholder">
  <?php include get_stylesheet_directory().'/include/header.php'; ?>
</header>

<main class="single product" id="single-product-container">
  <?php include get_stylesheet_directory() . '/include/product/product-header.php'; ?>

  <div class="product-body">
  <section class="product-slideshow">

    <?php

    // var_dump($photo);

    $edition = $photo['editions'][0];

    /*
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
    */ ?>
    <?php // include get_stylesheet_directory().'/include/product/slideshow.php'; ?>
    <div class="slideshow" id="slideshow">
      <div class="viewer">
        <div class="frame">
          <a><?php include get_stylesheet_directory().'/include/product/image.php'; ?></a>
        </div>
      </div>
    </div>


  </section>
  <section class="photo-details">
    <div class="single-columns">
      <div class="mobile-only">
        <?php include get_stylesheet_directory().'/include/attachment/detail-navigation.php'; ?>
      </div>
      <div class="single-column">
        <!-- <div class="photo-title-container"> -->
          <h1><span><?php echo $photo['title']; ?></span></h1>
        <!-- </div> -->
        <!-- <div class="placeholder"></div> -->

        <div class="details"></div>
        <?php // include get_stylesheet_directory().'/include/product/product-edition.php'; ?>


      </div>
      <div class="single-column">
        <div class="desktop-only">
          <?php include get_stylesheet_directory().'/include/attachment/detail-navigation.php'; ?>
        </div>
        <div class="about" id="about-work">
          <!-- <h2 class="mobile-only">
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
              <path d="M7.09471 14.4375L11.804 9.72815L16.3861 14.3102L18.0407 12.6556L13.4587 8.07352L11.6768 6.29161L10.0221 7.94624L5.3128 12.6556L7.09471 14.4375Z" fill="black"/>
            </svg>
            <span><?php echo __('About the work', 'actwall'); ?></span>
          </h2> -->
          <div class="slider"><div class="content"><?php echo apply_filters('the_content', $photo['content']); ?></div></div>
          <?php /* <a class="more">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
              <rect width="15.48" height="15.66" fill="white"/>
              <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="#C9C8C8"/>
            </svg>
            <span><?php echo __('Explore portfolio', 'actwall'); ?></span>
          </a>  */ ?>
        </div>
        <?php /*
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
        */ ?>
      </div>
    </div>
  </section>
  <section class="other-works">
    <div class="headline"><h3><?php echo __('All works', 'actwall'); ?></h3></div>
    <?php //do_action('actwall_member_photos', $post->post_parent); ?>
    <ul class="member-photos"></ul>
    <div class="more">
      <a href="<?php echo get_permalink($post->post_parent); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
          <path d="M6.48 0V6.66H0V9H6.48V15.66H9V9H15.48V6.66H9V0H6.48Z" fill="black"/>
        </svg>
        <span><?php echo __('See more', 'actwall'); ?></span>
      </a>
    </div>
  </section>
  <?php // do_action('actwall_photo_shows', $post->ID); ?>
  <?php // do_action('actwall_member_publications', $post->post_parent); ?>

</div>
<?php // include get_stylesheet_directory().'/include/contact.php'; ?>
</main>

<script>
  addEventListener("DOMContentLoaded", event => {
    // const product = new Actwall.SingleProduct();
    Actwall.SingleProduct.current = new Actwall.SingleProduct();
    Actwall.SingleProduct.current.results = <?php echo json_encode($results); ?>;
    Actwall.SingleProduct.current.nopopup = true;
    // product.render = async () => {
    //   await abduct(document.getElementById("single-product-container"), [...product.buildHeader(), ...product.buildBody()]);
    //   await abduct(document.getElementById("popup-viewinroom"), product.buildViewInRoom());
    // };




    history.replaceState({productId: Actwall.SingleProduct.current.results.photo.id, formatIndex: 0}, null);

    Actwall.SingleProduct.current.render();

  });


  {
    const header = document.getElementById("header");
    const headerBottom = document.getElementById("header-bottom");
    new Sticky2(
      headerBottom,
      window,
      () => header.getBoundingClientRect().height, // header.clientHeight,
      () => header.getBoundingClientRect().height  // header.clientHeight
    );
  }

</script>

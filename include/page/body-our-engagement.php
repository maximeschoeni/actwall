<header id="header" class="main-header">
  <?php include get_stylesheet_directory().'/include/header.php'; ?>
  <?php // do_action('actwall_page_our_engagement_header'); ?>
</header>
<header class="main-header placeholder">
  <?php include get_stylesheet_directory().'/include/header.php'; ?>
</header>

<main class="page our-engagement">

  <div id="header-newsletter" class="header-newsletter">
    <div class="page-title">
      <h1><?php the_title(); ?></h1>
      <!-- <a class="close" href="<?php echo home_url(); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
          <path d="M20.0839 0.991951L11.6636 9.41221L3.67166 1.42026L1.4203 3.67162L9.41225 11.6636L0.991987 20.0838L3.39286 22.4847L11.8131 14.0644L19.8051 22.0564L22.0564 19.805L14.0645 11.8131L22.4847 3.39283L20.0839 0.991951Z" fill="black"/>
        </svg>
      </a> -->
    </div>
    <div class="newsletter-link"><a target="_blank" href="https://04ca2d7d.sibforms.com/serve/MUIFAFBni6NPiupmJ4Rk3eenrmyIIWFLk0ls5km-ZhChUwt6D3CaacTANVkWT8mPNz_boh9V7Ye7RT30rtSM4fW8ctqgwkKrT7HGOtbPAFcC1X8QcE_y4XVwgXFMAiaQ28iCycGOczPmGkJHEujVMaZYidYcU-8hf-9qziCtHEu1v53EjUxJL9q02pGyk7KukIfOQ6CD84DWL3yY">subscribe to our newsletter</a></div>
  </div>

  <div class="header-newsletter placeholder">
    <div class="page-title">
      <h1><?php the_title(); ?></h1>
      <!-- <a class="close" href="<?php echo home_url(); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
          <path d="M20.0839 0.991951L11.6636 9.41221L3.67166 1.42026L1.4203 3.67162L9.41225 11.6636L0.991987 20.0838L3.39286 22.4847L11.8131 14.0644L19.8051 22.0564L22.0564 19.805L14.0645 11.8131L22.4847 3.39283L20.0839 0.991951Z" fill="black"/>
        </svg>
      </a> -->
    </div>
    <div class="newsletter-link"><a target="_blank" href="https://04ca2d7d.sibforms.com/serve/MUIFAFBni6NPiupmJ4Rk3eenrmyIIWFLk0ls5km-ZhChUwt6D3CaacTANVkWT8mPNz_boh9V7Ye7RT30rtSM4fW8ctqgwkKrT7HGOtbPAFcC1X8QcE_y4XVwgXFMAiaQ28iCycGOczPmGkJHEujVMaZYidYcU-8hf-9qziCtHEu1v53EjUxJL9q02pGyk7KukIfOQ6CD84DWL3yY">subscribe to our newsletter</a></div>
  </div>

  <div class="page-columns">
    <div class="page-column">
      <div class="title-block"><h1><?php echo get_post_meta($post->ID, 'tagline', true); ?></h1></div>
      <div class="subtitle-block"><h2><?php echo __('Contact', 'actwall'); ?></h2></div>
      <div class="page-column-contact">
        <table>
          <?php $address = get_post_meta($post->ID, 'address', true); ?>
          <?php if ($address) { ?>
            <tr>
              <!-- <td class="arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                  <path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="black"/>
                </svg>
              </td> -->
              <td>
                <span><?php echo get_post_meta($post->ID, 'address', true); ?></span>
              </td>
            </tr>
          <?php } ?>
          <?php $email = get_post_meta($post->ID, 'email', true); ?>
          <?php if ($email) { ?>
            <tr>
              <!-- <td class="arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                  <path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="black"/>
                </svg>
              </td> -->
              <td>
                <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
              </td>
            </tr>
          <?php } ?>
          <?php $phone = get_post_meta($post->ID, 'phone', true); ?>
          <?php if ($phone) { ?>
            <tr>
              <!-- <td class="arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                  <path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="black"/>
                </svg>
              </td> -->
              <td>
                <a href="<?php echo str_replace(array('(0)', '.', ' '), array('', '', ''), $phone); ?>"><?php echo $phone; ?></a>
              </td>
            </tr>
          <?php } ?>
          <?php $instagram = get_post_meta($post->ID, 'instagram', true); ?>
          <?php if ($instagram) { ?>
            <tr>
              <!-- <td class="arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                  <path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="black"/>
                </svg>
              </td> -->
              <td>
                <a href="<?php echo $instagram; ?>" target="_blank">Instagram</a>
              </td>
            </tr>
          <?php } ?>
        </table>
      </div>
      <div class="subtitle-block"><h2><?php echo __('Team', 'actwall'); ?></h2></div>
      <?php $team = get_post_meta($post->ID, 'team'); ?>
      <?php if ($team) { ?>
        <table>
          <?php foreach ($team as $row) { ?>
            <?php $row = (array) $row; ?>
            <tr>
              <!-- <td class="arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                  <path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="black"/>
                </svg>
              </td> -->
              <td>
                <span><?php echo $row['name']; ?></span>
              </td>
              <td>
                <span><?php echo $row['department']; ?></span>
              </td>
            </tr>
          <?php } ?>
        </table>
      <?php } ?>
    </div>
    <div class="page-column">
      <div class="content"><?php the_content(); ?></div>
    </div>
  </div>
  <?php // include get_stylesheet_directory().'/include/contact.php'; ?>
</main>
<script>
  {
    const header = document.getElementById("header");
    // const photographersContainer = document.getElementById("photographers-container");
    // const containerFilters = document.getElementById("filters-container");
    // const buttonFilterContainer = document.getElementById("button-filters-container");
    // const headerFilterContainer = document.getElementById("header-categories-container");
    // const headerFilter = document.getElementById("header-filter");
    const headerNewsletter = document.getElementById("header-newsletter");

    // new Sticky(
    //   containerFilters,
    //   () => {
    //     // console.log(header.clientHeight + Math.max(0, containerFilters.clientHeight - window.innerHeight));
    //     return header.clientHeight + Math.max(0, headerFilter.clientHeight + containerFilters.clientHeight - window.innerHeight)
    //   },
    //   () => header.clientHeight + headerFilter.clientHeight + headerNewsletter.clientHeight
    // );

    new Sticky(
      headerNewsletter,
      () => header.clientHeight,
      () => header.clientHeight
    );


  }
</script>

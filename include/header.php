<div class="header-top" id="header-top">

  <div class="slider" id="slider">
    <div class="navigation-left">
      <div class="site-title">
        <a href="<?php echo home_url(); ?>">Actwall</a>
      </div>
      <div class="site-tagline">
        <span><?php echo get_option('blogdescription'); ?></span>
      </div>
      <div class="close" onclick="document.body.classList.toggle('menu-open')">
        <div class="picto">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
            <path d="M11.3858 0.582052L6.67643 5.29138L2.09438 0.709331L0.43975 2.36396L5.0218 6.94601L0.312471 11.6553L2.09438 13.4373L6.80371 8.72792L11.3858 13.31L13.0404 11.6553L8.45834 7.07329L13.1677 2.36396L11.3858 0.582052Z" fill="black"/>
          </svg>
        </div>
      </div>
    </div>
    <div class="navigation">
      <div class="nav-header">
        <a href="#" class="login"><span>Login</span></a>
        <a href="#" class="signup"><span>Sign up</span></a>
        <?php do_action('sublanguage_print_language_switch'); ?>
        <div class="close" onclick="document.body.classList.toggle('menu-open')">
          <div class="picto">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
              <path d="M11.3858 0.582052L6.67643 5.29138L2.09438 0.709331L0.43975 2.36396L5.0218 6.94601L0.312471 11.6553L2.09438 13.4373L6.80371 8.72792L11.3858 13.31L13.0404 11.6553L8.45834 7.07329L13.1677 2.36396L11.3858 0.582052Z" fill="black"/>
            </svg>
          </div>
        </div>
      </div>
      <div class="menu more-menu">
        <?php require_once get_stylesheet_directory().'/walker/custom-walker.php'; ?>
        <div class="mobile-only">
          <?php wp_nav_menu(array(
            'theme_location' => 'top_menu',
            'container' => false,
            'menu_class' => 'top-menu',
            'walker' => new Actwall_Walker_Nav_Menu()
          )) ?>
        </div>
        <?php wp_nav_menu(array(
          'theme_location' => 'more_menu',
          'container' => false,
          'menu_class' => 'more-menu',
          'walker' => new Actwall_Walker_Nav_Menu()
        )) ?>
      </div>
      <!-- <div class="nav-footer">
        <div class="search">
          <div class="arrow">
            <div class="picto">
              <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                <path d="M6.59 0L13.99 7.29H0V9.72H13.99L6.59 17.01H9.99L18.49 8.51L9.99 0H6.59Z" fill="black"/>
              </svg>
            </div>
            <span>Search</span>
          </div>
          <input type="text" name="search" placeholder="Search by keyword, theme, artists, gallery, institutions"/>
        </div>
      </div> -->
    </div>
  </div>
  <div class="logo">
    <?php /* if (is_singular('product')) { ?>
      <a href="<?php echo home_url(); ?>" class="content desktop-only">Actwall</a>
      <span class="content mobile-only"><?php echo get_the_title(get_queried_object()->post_parent); ?></span>
    <?php } else if (is_singular('member')) { ?>
      <a href="<?php echo home_url(); ?>" class="content desktop-only">Actwall</a>
      <span class="content mobile-only"><?php echo get_the_title(get_queried_object()); ?></span>
    <?php } else if (is_page('work-to-sale')) { ?>
      <a href="<?php echo home_url(); ?>" class="content desktop-only">Actwall</a>
      <span class="content mobile-only"><?php echo get_the_title($member); ?></span>
    <?php } else { ?>
      <a href="<?php echo home_url(); ?>" class="content">Actwall</a>
    <?php } */ ?>
    <a href="<?php echo home_url(); ?>" class="content">Actwall</a>
  </div>
  <div class="top-menu-container desktop-only">
    <?php wp_nav_menu(array(
      'theme_location' => 'top_menu',
      'container' => false,
      'menu_class' => 'top-menu',
      'walker' => new Actwall_Walker_Nav_Menu()
    )) ?>
  </div>
  <div class="burger" onclick="document.body.classList.toggle('menu-open')">
    <div class="picto">
      <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
        <rect width="25" height="4" fill="black"/>
        <rect y="10" width="25" height="4" fill="black"/>
        <rect y="20" width="25" height="4" fill="black"/>
      </svg>
    </div>
  </div>

</div>
<script>
  // class ActwallSticky extends Sticky {
  //   getHeight() {
  //     return document.getElementById("header").clientHeight;
  //   }
  // }
  // new ActwallSticky(document.getElementById("header"), 0);

  new Sticky(document.getElementById("header"));



    // new Accordeon(document.querySelector("header"), ".burger", ".slider", ".close");
    //
    // if (window.innerWidth < 900) {
    //
    //   AccordeonItem.register(document.getElementById("menu-top-menu"), "li", "a", ".sub-menu");
    //
    // }
  // addEventListener("DOMContentLoaded", event => {
  //   const container = document.getElementById("header-top");
  //   abduct(container, [
  //     {
  //       class: "slider",
  //       children: [
  //         {
  //           children: [
  //             {
  //               children: [
  //                 {},
  //                 {},
  //                 {
  //                   class: "close",
  //                   update: element => {
  //                     element.onclick = event => {
  //                       document.body.classList.toggle("menu-open");
  //                     };
  //                   }
  //                 }
  //               ]
  //             },
  //             {}
  //           ]
  //         },
  //         {
  //           class: "navigation",
  //           children: [
  //             {
  //               class: "nav-header",
  //               children: [
  //                 {
  //                   class: "login"
  //                 },
  //                 {
  //                   class: "signup"
  //                 },
  //                 {
  //                   class: "language-switch",
  //                 }
  //               ]
  //             },
  //             {
  //               class: "menu"
  //             },
  //             {
  //               class: "tools"
  //             }
  //           ]
  //         }
  //       ]
  //     },
  //     {
  //       class: "logo"
  //     },
  //     {
  //       class: "burger",
  //       update: element => {
  //         element.onclick = event => {
  //           document.body.classList.toggle("menu-open");
  //         };
  //       }
  //     }
  //   ]);
  // });
</script>

<?php get_header(); ?>
<?php // @var_dump($_COOKIE['intro']); ?>
<body class="home <?php //if (empty($_COOKIE['intro']) || isset($_GET['intro'])) echo 'intro active'; ?>">
  <header id="header" class="main-header">
    <?php include get_stylesheet_directory().'/include/header.php'; ?>
    <?php //do_action('actwall_home_header'); ?>
  </header>
  <div class="intro" id="intro">
    <div class="intro-bg"></div>
    <div class="intro-title"><h1>Actwall</h1></div>
    <div class="intro-headline">
      <div class="intro-headline-column">
        <p>The first curated platform in Switzerland to bring to the fore rare photographic works previously unseen on the market, all signed by Swiss-based authors.</p>
      </div>
      <div class="intro-headline-column">
        <p>We make the acquisition process less intimidating and more transparent. Acquiring works of art is one of the most direct ways of supporting artist.</p>
      </div>
    </div>
  </div>
  <script>
    {
      const element = document.getElementById("intro");
      if (element && element.clientWidth > 0) {
        const onstart = event => {
          event.preventDefault();
          element.classList.add("complete");
          element.ontransitionend = event => {
            element.classList.add("done");
            removeEventListener("click", onstart);
            removeEventListener("wheel", onstart);
            element.ontransitionend = null;
          }
        };
        addEventListener("click", onstart);
        addEventListener("wheel", onstart, {passive: false});
      }
    }
  </script>
  <main class="home">
    <?php //do_action('actwall_home_header'); ?>
    <?php do_action('actwall_home'); ?>

  </main>

  <?php include get_stylesheet_directory().'/include/footer.php'; ?>
	<?php wp_footer(); ?>

  <script>
    // Actwall.intro = document.body.classList.contains("active");
    // if (Actwall.intro) {
    //   window.onbeforeunload = function () {
    //     window.scrollTo(0, 0);
    //   }
    //   // window.scrollTo(0, 0);
    //   Actwall.onIntro = event => {
    //     if (Actwall.intro) {
    //       if (event) event.preventDefault();
    //       document.body.classList.remove("active");
    //       setCookie("intro", "1", 1);
    //       Actwall.intro = false;
    //       var duration = getComputedStyle(document.body).getPropertyValue('--intro-duration');
    //       setTimeout(() => void document.body.classList.remove("intro"), parseInt(duration));
    //     }
    //   }
    //   document.addEventListener("click", Actwall.onIntro);
    //   document.addEventListener("scroll", Actwall.onIntro);
    //   setTimeout(Actwall.onIntro, 2500000);
    // }
  </script>
</body>
<?php get_footer(); ?>

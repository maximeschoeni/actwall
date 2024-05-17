<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/template-portfolio.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/player-v5.js"></script>
<?php if ($series_query->posts) { ?>
  <?php foreach ($series_query->posts as $serie) { ?>
    <section class="serie" id="serie-<?php echo $serie->ID; ?>">
      <?php do_action('actwall_serie', $serie->ID); ?>
      <div class="single-columns">
        <div class="single-column">
          <div class="serie-header">
            <h2><?php echo get_the_title($serie->ID); ?></h2>
          </div>
          <?php include get_stylesheet_directory().'/include/attachment/acquire-button.php'; ?>
        </div>
        <div class="single-column">
          <?php include get_stylesheet_directory().'/include/attachment/detail-navigation.php'; ?>
          <div class="serie-description">
            <div class="text"><?php echo apply_filters('the_content', $serie->post_content); ?></div>
          </div>
        </div>
      </div>
      <div id="slideshow-container-<?php echo $serie->ID; ?>"></div>



    </section>

    <script>
      {
        const section = document.getElementById("serie-<?php echo $serie->ID; ?>");
        const imageContainer = section.querySelector("ul.member-photos");
        const container = document.getElementById("slideshow-container-<?php echo $serie->ID; ?>");
        const portfolio = new Actwall.PortfolioSlideshow(imageContainer, "<?php echo get_the_title($serie->ID); ?>");
        abduct(container, portfolio.build());
      }

    </script>
  <?php } ?>
<?php } ?>

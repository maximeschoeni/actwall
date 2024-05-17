<?php get_header(); ?>
<body class="attachment single photo">
  <header id="header">
    <?php include get_stylesheet_directory().'/include/header.php'; ?>

  </header>

  <main>
    <?php do_action('actwall_photo_body'); ?>
  </main>
  <?php include get_stylesheet_directory().'/include/footer.php'; ?>
	<?php wp_footer(); ?>

</body>
<?php get_footer(); ?>

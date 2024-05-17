
<?php get_header(); ?>
<body class="attachment single member">
  <header id="header" class="main-header">
    <?php include get_stylesheet_directory().'/include/header.php'; ?>
  </header>
  <header class="main-header placeholder">
    <?php include get_stylesheet_directory().'/include/header.php'; ?>
  </header>

  <main class="single member">
    <?php do_action('actwall_member_header'); ?>
    <?php do_action('actwall_member_body'); ?>
    <?php // include get_stylesheet_directory().'/include/contact.php'; ?>
  </main>
  <?php include get_stylesheet_directory().'/include/footer.php'; ?>
	<?php wp_footer(); ?>

</body>
<?php get_footer(); ?>

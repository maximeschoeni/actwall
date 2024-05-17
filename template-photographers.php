<?php /* Template Name: Photographers and Members */ ?>

<?php get_header(); ?>
<body class="page photographers">
  <header id="header" class="main-header">
    <?php include get_stylesheet_directory().'/include/header.php'; ?>
  </header>
  <header class="main-header placeholder">
    <?php include get_stylesheet_directory().'/include/header.php'; ?>
  </header>

  <main class="page photographers">
    <?php do_action('actwall_page_photographers'); ?>
    <?php // include get_stylesheet_directory().'/include/contact.php'; ?>
  </main>
  <?php include get_stylesheet_directory().'/include/footer.php'; ?>
	<?php wp_footer(); ?>

</body>
<?php get_footer(); ?>

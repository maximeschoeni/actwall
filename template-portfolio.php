<?php /* Template Name: Portfolio */ ?>

<?php get_header(); ?>
<body class="single portfolio">
  <header id="header" class="main-header">
    <?php include get_stylesheet_directory().'/include/header.php'; ?>
  </header>

  <main>
    <?php do_action('actwall_portfolio'); ?>
    <?php // include get_stylesheet_directory().'/include/contact.php'; ?>
  </main>
  <?php include get_stylesheet_directory().'/include/footer.php'; ?>
	<?php wp_footer(); ?>

</body>
<?php get_footer(); ?>

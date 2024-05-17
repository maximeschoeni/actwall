<?php die('index.php'); ?>
<?php get_header(); ?>
<body class="home">
  <?php include get_stylesheet_directory().'/include/header.php'; ?>
  <?php do_action('actwall_home'); ?>
  <?php include get_stylesheet_directory().'/include/footer.php'; ?>
	<?php wp_footer(); ?>
</body>
<?php get_footer(); ?>

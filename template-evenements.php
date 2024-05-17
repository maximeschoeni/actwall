<?php /* Template Name: Evenements */ ?>

<?php get_header(); ?>
<body class="page evenements">
  <header id="header" class="main-header">
    <?php include get_stylesheet_directory().'/include/header.php'; ?>
  </header>

  <main>
    <?php do_action('actwall_page_evenements'); ?>
  </main>
  <?php include get_stylesheet_directory().'/include/footer.php'; ?>
	<?php wp_footer(); ?>

</body>
<?php get_footer(); ?>

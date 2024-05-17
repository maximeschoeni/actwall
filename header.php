<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0">
	<meta name="description" content="<?php /* do_action('actwall_site_description'); */ echo get_bloginfo('description'); ?>">
	<title><?php echo get_bloginfo('name'); ?><?php echo wp_title('|'); ?></title>
	<?php
		$options = get_option('actwall', array());
	?>
	<script>
		var Actwall = {
			rest_url: "<?php echo rest_url(); ?>",
			theme_url: "<?php echo get_stylesheet_directory_uri(); ?>",
			home_url: "<?php echo home_url(); ?>",
			defaultImageType: "<?php echo isset($options['photography_term']) ? $options['photography_term'][0] : '0'; ?>",
			isMobile: () => window.innerWidth < 900,
			cache: {},
			openProduct: (id, permalink, index) => {
				if (Actwall.SingleProduct) {
					Actwall.SingleProduct.open(id, permalink, index);
					return false;
				}
			}
		};
	</script>
	<?php
		$home_margin_v = isset($options['home_margin_v']) ? (float) $options['home_margin_v'] : 20;
		$home_margin_h = isset($options['home_margin_h']) ? (float) $options['home_margin_h'] : 20;
		$scale = isset($options['home_image_size']) ? (float) $options['home_image_size'] : 0.3;
		$margin_v = $scale*$home_margin_v;
		$margin_h = $scale*$home_margin_h;
	?>

	<!-- <style>
		ul.grid li {
			margin: <?php echo $margin_v; ?>em <?php echo $margin_h; ?>em;
		}
	</style> -->
	<?php include get_stylesheet_directory().'/include/translations.php'; ?>
	<?php wp_head(); ?>
</head>

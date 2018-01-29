<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<?php
	echo get_theme_mod('code-head') . "\n";
	?>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">

	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="/manifest.json">
	<meta name="theme-color" content="#ffffff">
	<?php wp_head(); ?>
	<script>
	window.addEventListener('DOMContentLoaded', initHeader);

	var didScroll = true;
	var prefPageYOffset = 0;

	function initHeader(){

		window.addEventListener('scroll', function(e){ didScroll = true;});

		setInterval(function() {
		    if(didScroll) {
		       didScroll = false;
		       pageOnScroll();
		        //console.log('You scrolled');
		    }
		}, 100);
	}

	function pageOnScroll(){
		if(window.pageYOffset > 50){
			if(!document.body.classList.contains('scrolled-down')){
				document.body.classList.add('scrolled-down');
			}
		}
		else {
			document.body.classList.remove('scrolled-down');
		}

		if(window.pageYOffset > 500){
			if(!document.body.classList.contains('scrolled-down-content')){
				document.body.classList.add('scrolled-down-content');
			}
		}
		else {
			document.body.classList.remove('scrolled-down-content');
		}

		prefPageYOffset = window.pageYOffset;
	}
	</script>
</head>
<body <?php body_class(); ?>>
<?php
echo get_theme_mod('code-body') . "\n";
?>
<div class="header-wrapper">
 	<div class="header-content">
	 	<?php
		$webpack_helper = new WebpackHelper();
		?>
 		<div class="header-logo-wrapper">
			<a class="header-logo" href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/<?php echo $webpack_helper->getHashedAssetUrl( 'logo.svg' ); ?>" alt="<?php bloginfo('name'); ?>" /></a>
		</div>
		<button id="header-menu" class="header-menu-button" aria-label="menu">
			<span class="header-menu-button-icon"></span>
		</button>
		<div id="header-nav-wrapper" class="header-nav-wrapper">
			<nav id="header-nav" class="header-nav">
				<?php wp_nav_menu(array('theme_location' => 'headernav', 'container' => ''));?>
			</nav>
		</div>
		<?php get_template_part('includes/include', 'header-download'); ?>
	</div>
</div>
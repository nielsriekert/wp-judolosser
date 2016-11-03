<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<?php
	echo get_theme_mod('code-head') . "\n";
	?>
	<title><?php bloginfo('name'); ?> | <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">

	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>?v=1.0.0" />

	<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
	<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
	<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="msapplication-TileImage" content="/mstile-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<?php wp_head(); ?>
	<script>
	window.addEventListener('load', initHeader);

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
		<a class="header-logo" href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/logo.svg" alt="<?php bloginfo('name'); ?>" /></a>
		<button id="header-menu" class="header-menu-button">
			<div class="header-menu-button-icon">
		</button>
		<nav id="header-nav" class="header-nav">
			<?php wp_nav_menu(array('theme_location' => 'headernav', 'container' => ''));?>
		</nav>
	</div>
</div>
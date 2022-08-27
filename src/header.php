<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
do_action( 'jl-theme/body' );
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
		<?php
		AttachmentView::viewAttachmentsHeaderDropDown( AttachmentModel::getAttachments() );
		?>
	</div>
</div>
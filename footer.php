	<div class="footer-wrapper">
		<footer class="content footer">
			<div class="footer-column">
				<h2>Trainingstijden</h2>
				<?php echo get_training_times(false, array('tijd', 'training')); ?>
			</div>
			<div class="footer-column">
				<p>
				<a href="mailto:info@judolosser.nl">info@judolosser.nl</a>
				</p>
			</div>
			<nav id="footer-nav" class="footer-nav">
				<?php wp_nav_menu(array('theme_location' => 'footernav', 'container' => ''));?>
			</nav>
		</footer>
	</div>
	<?php wp_footer(); ?>
	</body>
</html>
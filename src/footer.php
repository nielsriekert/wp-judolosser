	<div class="footer-wrapper">
		<footer class="content footer">
			<div class="footer-column footer-training-times">
				<h2>Trainingstijden</h2>
				<?php echo get_training_times(false, array('tijd', 'training')); ?>
			</div>
			<div class="footer-column">
			<?php
			$events = new WP_Query(array(
				'post_type' => 'event',
				'posts_per_page' => 8,
				'meta_key' => 'e_datum',
				'orderby' => 'meta_value',
				'order' => 'ASC',
				'meta_query' => array(
					array(
						'key' => 'e_datum',
						'value' => date('Y-m-d'),
						'compare' => '>=',
						'type' => 'DATE'
					)
				)
			));

			if($events->have_posts()){?>
				<h2>Agenda</h2>
				<ul class="footer-events">
				<?php
				while($events->have_posts()){ $events->the_post();
					echo '<li class="footer-event"><a href="' . get_permalink() . '">' . get_the_title() .  ' (' . humanize_date(CFS()->get('e_datum'), 'j F') . ')</a></li>';
				}
				?>
				</ul>
				<?php
			}
				?>
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
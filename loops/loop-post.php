<div class="article-item">
	<a href="<?php the_permalink(); ?>">
		<?php
		if(has_post_thumbnail()) {?>
		<div class="article-item-thumb">
			<?php the_post_thumbnail('post-thumb'); ?>
		</div>
			<?php
		}
		?>
		<div class="article-item-body">
			<h2 class="article-item-title"><?php the_title(); ?></h2>
			<div class="article-item-date">
				<?php
				if(get_post_type() == 'event' || get_post_type() == 'photoalbum'){
					echo humanize_date(CFS()->get('e_datum'));
				} else {
					echo humanize_date(get_the_time('Ymd'));
				}
				?>
			</div>
		</div>
	</a>
</div>
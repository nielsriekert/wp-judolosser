<div class="article-item">
	<a href="<?php the_permalink(); ?>">
		<?php
		if(has_post_thumbnail()){?>
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
				if(get_post_type() == 'event'){
					if(CFS()->get('e_datum')){
						echo humanize_date(CFS()->get('e_datum'));
					}
				}
				else if(get_post_type() == 'photoalbum'){
					$events = new WP_Query( array(
						'connected_type' => 'event_to_photoalbum',
						'connected_items' => get_post(),
						'nopaging' => true,
					));

					if($events->post_count == 1){
						echo humanize_date(CFS()->get('e_datum', current($events->posts)->ID));
					}

				}
				else {
					echo humanize_date(get_the_time('Ymd'));
				}
				?>
			</div>
			<?php
			if(!has_post_thumbnail()){?>
			<div class="article-item-excerpt">
				<?php the_excerpt(); ?>
			</div>
				<?php
			}
			?>
		</div>
	</a>
</div>
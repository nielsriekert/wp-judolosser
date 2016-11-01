<?php
/*
Template Name: Contact
*/
get_header(); ?>
<div class="content article">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
	<?php
	$users = get_users(); 
	for($i=count($users)-1;$i>=0;$i--){
		if(empty($users[$i]->boardmember)){unset($users[$i]);};
	}

	if($users){
		?>
	<ul class="users">
		<?php
		foreach($users as $user){
			include(locate_template('loops/loop-user.php'));
		}
		?>
	</ul>
		<?php
	}
	?>
</div>
<?php get_footer(); ?>
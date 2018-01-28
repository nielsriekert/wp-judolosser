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
		$boardmember_roles = get_bestuursrollen();
		?>
	<ul class="users">
		<?php
		foreach($boardmember_roles as $role){
			foreach($users as $user){
				if($role == $user->boardmember){
					include(locate_template('loops/loop-user.php'));
				}
			}
		}
		?>
	</ul>
		<?php
	}
	?>
</div>
<?php get_footer(); ?>
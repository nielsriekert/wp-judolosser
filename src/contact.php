<?php
/*
Template Name: Contact
*/
get_header(); ?>
<div class="content article">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
	<?php
	$committees = USERS()->getCommittees();

	$users_by_committee = array_map( function( $committee) {
		return (object) array(
			'name' => $committee,
			'users' => UserModel::getUsersByCommittee( $committee )
		);
	}, $committees );

	$users_by_committee = array_filter( $users_by_committee, function( $committee ) {
		return count( $committee->users ) > 0;
	} );

	if( count( $users_by_committee ) > 0 ) { ?>
		<ul class="committees-container">
		<?php
		foreach( $users_by_committee as $committee ) { ?>
			<li class="committee-container">
				<h2><?php echo $committee->name; ?></h2>
				<?php
				if( count( $committee->users ) > 0 ) { ?>
					<ul class="users-committee-container">
						<?php
						foreach( $committee->users as $user ) {
							UserView::viewCommitteeMember( $user );
						}
						?>
					</ul>
					<?php
				}
				?>
			</li>
			<?php
		}
		?>
		</ul>
		<?php
	}
	?>
</div>
<?php get_footer(); ?>
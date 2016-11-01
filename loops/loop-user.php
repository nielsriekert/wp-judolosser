<li class="user-item">
	<h2 class="user-name"><?php echo $user->first_name . ' ' . $user->last_name; ?></h2>
	<p class="user-boardmember"><?php echo $user->boardmember; ?></p>
	<p>
		<a href="mailto:<?php echo $user->user_email; ?>"><?php echo $user->user_email; ?></a>
	</p>
</li>
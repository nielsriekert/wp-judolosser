<li class="user-item">
	<h2 class="user-name"><?php echo $user->first_name . ' ' . $user->last_name; ?></h2>
	<p class="user-boardmember"><?php echo $user->boardmember; ?></p>
	<?php
	$user_info = array(
		'address-number' => $user->address,
		'`postcode' => $user->postcode,
		'town' => $user->town,
		'phone-home' => $user->phonehome,
		'phone' => $user->phone,
		'email' => $user->user_email
	);
	$user_info = array_filter($user_info);
	$i = 0;
	if(count($user_info) > 0){
		echo '<p class="user-contact">';
		foreach($user_info as $key => $info){$i++;
			switch($key){
				case 'phone-home':
				case 'phone':
					echo '<a href="tel:' . preg_replace('/(\(0\))?[^0-9\+]?/', '', $info) . '">' . $info . '</a>';
					break;
				case 'email':
					echo '<a href="mailto:' . $info . '">' . $info . '</a>';
					break;
				default:
					echo $info;
					break;
			}
			if(count($user_info) != $i){
				echo '<br />';
			}
		}
		echo '</p>';
	}
	?>
</li>
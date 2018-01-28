<?php
switch(get_post_type()){
	case 'event':
		if(CFS()->get('e_datum')){
		?>
<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "Event",
	"name": "<?php echo get_the_title(); ?>",
	"startDate": "<?php echo date_i18n('c', strtotime(CFS()->get('e_datum'))); ?>",
	<?php
	$locatie = CFS()->get('e_locatie');
	$adres = CFS()->get('e_adres');
	$postcode = CFS()->get('e_postcode');
	$plaats = CFS()->get('e_plaats');
	$land = CFS()->get('e_land');
	if($locatie && $adres && $postcode && $plaats && $land){
	?>
	"location": {
		"@type": "Place",
		"name": "<?php echo $locatie; ?>",
		"address": "<?php echo $adres; ?>, <?php echo $postcode; ?>, <?php echo $plaats; ?>, <?php echo $land; ?>"<?php $website = CFS()->get('e_website'); if($website){?>,
		"sameAs" : "<?php echo $website; ?>"
		<?php
		}
		?>
		}
	},
	<?php
	}
	?>
}
</script>
		<?php
		}
		break;
}
?>
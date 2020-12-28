<?php
class LocationView {

	/**
	 * @return void
	 */
	public static function viewLocation( Location $location ) { ?>
		<div class="location-container">
			<div class="location-info-container">
				<h3 class="location-name"><?php echo $location->getName(); ?></h3>
				<address class="location-address">
					<?php
					echo $location->getAddress();
					if( $location->getAddress() && $location->getPostcodeAndLocality() ) { ?>
						<br>
						<?php
					}
					echo $location->getPostcodeAndLocality(); ?>
				</address>
			</div>
			<?php
			if( $location->hasFeaturedPhoto() ) { ?>
				<div class="location-image-container">
					<?php self::viewLocationImage( $location->getFeaturedPhoto() ); ?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	public static function viewLocationImage( LocationImage $location_image ) { ?>
		<img class="location-image" src="<?php echo $location_image->getSrc(); ?>" />
		<?php
	}
}
<?php $person = new FooPlugins\FooPeople\objects\Person($post);

// var_dump($person);
?>

<li class="ppl__listing-item" data-search="<?php echo $person->search ?>">

	<header class="ppl__card_header"></header>

	<figure class="ppl__card_portrait_wrapper">
		<?php echo get_the_post_thumbnail( $person->ID, 'thumbnail', array('class' => 'ppl__card_portrait_thumbnail') ); ?>
	</figure>

	<div class="ppl__card_details">

		<h2 class="ppl__card_name / ppl__heading">
			<?php echo $person->name; ?>
			<?php if ( !empty( $person->main_details['preferred'] ) ) : ?>
				<?php echo '('.$person->main_details['preferred'].')'; ?>
			<?php endif; ?>
		</h2>

		<?php if ( isset( $person->main_details['jobtitle'] ) ) : ?>
		<div class="ppl__card_particulars">
			<div class="ppl__card_work_title">
				<i class="ppl_icon-user"></i>
				<?php echo $person->main_details['jobtitle']; ?>
			</div>
		</div>
		<?php endif; ?>


		<?php if( !empty($person->teams()) ) : ?>
		<div class="ppl__card_particulars">
			<i class="ppl_icon-group"></i>
			<?php
				foreach ($person->teams() as $team) {
					echo '<span class="ppl__item_pipe">';
					echo ' '.$team.' ';
					echo '</span>';
				}
			?>
		</div>
		<?php endif; ?>


		<?php if ( isset( $person->main_details['role'] ) ) : ?>
		<div class="ppl__card_particulars">
			<i class="ppl_icon-user"></i>
			<?php
			foreach ($person->roles() as $role) {
				echo '<span class="ppl__item_pipe">';
				echo ' '.$role.' ';
				echo '</span>';
			}
			?>
		</div>
		<?php endif; ?>


		<div class="ppl__card_more-details">
			<div class="ppl__card_particulars-wrapper">

				<?php if( !empty($person->locations()) ) : ?>
				<div class="ppl__card_particulars">
					<i class="ppl_icon-map-marker"></i>
					<?php
						foreach ($person->locations() as $location) {
							echo '<span class="ppl__card_location / ppl__item_pipe">';
							echo ' '.$location.' ';
							echo '</span>';
						}
					?>
				</div>
				<?php endif; ?>


				<?php if ( isset( $person->main_details['workemail'] ) ) : ?>
				<div class="ppl__card_particulars ppl__card_email">
					<a href="mailto:<?php echo $person->main_details['workemail']; ?>">
						<i class="ppl_icon-envelope "></i>
						<?php echo $person->main_details['workemail']; ?>
					</a>
				</div>
				<?php endif; ?>

				<?php if ( isset( $person->main_details['workmobile'] ) ) : ?>
				<div class="ppl__card_particulars ppl__card_contactnumber">
					<a href="tel:<?php echo $person->main_details['workmobile']; ?>">
						<i class="ppl_icon-phone"></i>
						<?php echo $person->main_details['workmobile']; ?>
					</a>
				</div>
				<?php endif; ?>


				<a class="ppl__button" href="<?php echo $person->permalink ?>">
					View Full profile
				</a>

			</div>
		</div>

	</div>

</li>
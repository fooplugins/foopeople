<?php $person = new FooPlugins\FooPeople\objects\Person($post); ?>

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

		<div class="ppl__card_particulars">
			<?php if ( isset( $person->main_details['jobtitle'] ) ) : ?>
			<div class="ppl__card_work_title">
				<i class="ppl_icon-user"></i>
				<?php echo $person->main_details['jobtitle']; ?>
			</div>
			<?php endif; ?>

			<?php if( !empty($person->teams()) ) : ?>
			<div class="ppl__card_work_team">
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
		</div>



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

				<div class="ppl__card_particulars">
					<?php if ( isset( $person->main_details['email'] ) ) : ?>
					<span class="ppl__card_email / ppl__item_pipe">
						<a href="mailto:<?php echo $person->main_details['email']; ?>">
							<i class="ppl_icon-envelope "></i>
							<?php echo $person->main_details['email']; ?>
						</a>
					</span>
					<?php endif; ?>

					<?php if ( isset( $person->main_details['phonenumber'] ) ) : ?>
					<span class="ppl__card_contactnumber / ppl__item_pipe">
						<a href="tel:<?php echo $person->main_details['phonenumber']; ?>">
							<i class="ppl_icon-phone"></i>
							<?php echo $person->main_details['phonenumber']; ?>
						</a>
					</span>
					<?php endif; ?>
				</div>


				<?php if( !empty($person->skills()) ) : ?>
				<div class="ppl__card_skills_wrapper">
					<div class="ppl__card_skills">
						<i class="ppl_icon-tag"></i>
					<?php
						foreach ($person->skills() as $skill) {
							echo '<span class="ppl__item_delimiter">';
							echo ' '.$skill.' ';
							echo '</span>';
						}
					?>
					</div>
				</div>
				<?php endif; ?>

			</div>
		</div>

	</div>

</li>
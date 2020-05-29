<?php
if ( !isset( $person ) ) return;

$departments = $person->departments();
$locations = $person->locations();
$skills = $person->skills();
?>
<li class="ppl__listing-item" data-search="<?php echo $person->search_index; ?>">

	<!-- <header class="ppl__card_header" style="background-image: url('http://127.0.0.1/pace-people/wp-content/plugins/pace-people/images/cover-photo-01.jpg')"></header> -->
	<header class="ppl__card_header"></header>

	<figure class="ppl__card_portrait_wrapper">
		<?php echo get_the_post_thumbnail( $person->ID, 'thumbnail', array('class' => 'ppl__card_portrait_thumbnail') ); ?>
	</figure>

	<div class="ppl__card_details">

		<h2 class="ppl__card_name / ppl__heading">
			<?php echo $person->name; ?>
		</h2>

		<div class="ppl__card_particulars">
			<?php if ( isset( $person->jobtitle ) ) : ?>
			<div class="ppl__card_work_title">
				<i class="ppl_icon-user"></i>
				<?php echo $person->jobtitle; ?>
			</div>
			<?php endif; ?>

			<?php if( !empty($departments) ) : ?>
			<div class="ppl__card_work_department">
				<i class="ppl_icon-group"></i>
				<?php
					foreach ($departments as $department) {
						echo '<span class="ppl__item_pipe">';
						echo ' '.$department.' ';
						echo '</span>';
					}
				?>
			</div>
			<?php endif; ?>
		</div>





		<div class="ppl__card_more-details">
			<div class="ppl__card_particulars-wrapper">

				<?php if( !empty($locations) ) : ?>
				<div class="ppl__card_particulars">
					<i class="ppl_icon-map-marker"></i>
					<?php
						foreach ($locations as $location) {
							echo '<span class="ppl__card_location / ppl__item_pipe">';
							echo ' '.$location.' ';
							echo '</span>';
						}
					?>
				</div>
				<?php endif; ?>

				<div class="ppl__card_particulars">
					<?php if ( isset( $person->email ) ) : ?>
					<span class="ppl__card_email / ppl__item_pipe">
						<a href="mailto:<?php echo $person->email; ?>">
							<i class="ppl_icon-envelope "></i>
							<?php echo $person->email; ?>
						</a>
					</span>
					<?php endif; ?>

					<?php if ( isset( $person->phonenumber ) ) : ?>
					<span class="ppl__card_contactnumber / ppl__item_pipe">
						<a href="tel:<?php echo $person->phonenumber; ?>">
							<i class="ppl_icon-phone"></i>
							<?php echo $person->phonenumber; ?>
						</a>
					</span>
					<?php endif; ?>
				</div>


				<?php if( !empty($skills) ) : ?>
				<div class="ppl__card_skills_wrapper">
					<div class="ppl__card_skills">
						<i class="ppl_icon-tag"></i>
					<?php
						foreach ($skills as $skill) {
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
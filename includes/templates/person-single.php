<?php
if( is_single() ) {
	get_header();
	global $post;
} else {
	$post = get_post($data['person_id']);
}
$person = new FooPlugins\FooPeople\objects\Person($post);
?>

<div id="foopeople" class="foopeople">
	<div class="ppl__single-person">

		<header class="ppl__card_header"></header>

		<figure class="ppl__card_portrait_wrapper">
			<?php echo get_the_post_thumbnail( $person->ID, 'thumbnail', array('class' => 'ppl__card_portrait_thumbnail') ); ?>
		</figure>

		<div class="ppl__card_details-single">

			<h1 class="ppl__card_name / ppl__heading">
				<?php echo $person->name; ?>

				<?php if ( !empty( $person->main_details['preferred'] ) ) : ?>
					<?php echo '('.$person->main_details['preferred'].')'; ?>
				<?php endif; ?>
			</h1>

			<?php if ( isset( $person->main_details['jobtitle'] ) ) : ?>
			<span class="ppl__card_work_title / ppl__heading">
				<?php echo $person->main_details['jobtitle']; ?>
			</span>
			<?php endif; ?>



			<?php if ( !empty( $person->main_details['manager']['display'] ) ) : ?>
			<div class="ppl__card_particulars">
				<div class="ppl__card_manager">
					<i class="ppl_icon-user ppl_icon-spacer "></i>
					Manager :
					<?php echo $person->main_details['manager']['display']; ?>
				</div>
			</div>
			<?php endif; ?>



			<?php if( !empty($person->teams()) ) : ?>
			<div class="ppl__card_particulars">
				<div class="ppl__card_team">
					<i class="ppl_icon-group ppl_icon-spacer"></i>
					<?php
						foreach ($person->teams() as $team) {
							echo '<span class="ppl__item_pipe">';
							echo ' '.$team.' ';
							echo '</span>';
						}
					?>
				</div>
			</div>
			<?php endif; ?>

			<?php if( !empty($person->locations()) ) : ?>
			<div class="ppl__card_particulars">
				<span class="ppl__card_location / ppl__item_pipe">
					<i class="ppl_icon-map-marker ppl_icon-spacer"></i>
					<?php
						foreach ($person->locations() as $location) {
							echo '<span class="ppl__card_location / ppl__item_pipe">';
							echo ' '.$location.' ';
							echo '</span>';
						}
					?>
				</span>
			</div>
			<?php endif; ?>

			<div class="ppl__card_particulars">
				<?php if ( isset( $person->main_details['email'] ) ) : ?>
				<span class="ppl__card_email / ppl__item_pipe">
					<a href="mailto:<?php echo $person->main_details['email']; ?>">
						<i class="ppl_icon-envelope ppl_icon-spacer"></i>
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
				<i class="ppl_icon-tag  ppl_icon-spacer"></i>
				<?php
					foreach ($person->skills() as $skill) {
						echo '<span class="ppl__item_delimiter">';
						echo ' '.$skill.' ';
						echo '</span>';
					}
				?>
			</div>
			<?php endif; ?>


		</div>
	</div>
</div>

<?php
if( is_single( ) ) {
	get_footer();
}
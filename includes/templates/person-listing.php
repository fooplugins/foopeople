<?php $people = foopeople_get_people($data['team']); ?>

<div id="foopeople" class="foopeople js-foopeople">

	<?php if($data['show_search']) : ?>
	<div class="ppl_search-wrapper">
		<input id="ppl__search" type="search" class="ppl_search-field js-foopeople-search" placeholder="<?php _e('Search for people by name, team or skill...', 'foopeople'); ?>">
	</div>
	<?php endif; ?>


	<?php if($data['team']) : ?>
	<h2 class="ppl__heading">
		<?php _e('Showing all people in ', 'foopeople'); ?>
		<?php echo '"'.foopeople_get_taxonomy_name( $data['team'], FOOPEOPLE_CT_TEAM ).'"';	?>
	</h2>
	<?php endif; ?>

<?php if ( $people->have_posts() ) : ?>
	<ol class="ppl_listing" data-ppl-columns="<?php echo foopeople_get_setting('listing_columns', '3'); ?>">
<?php while ( $people->have_posts() ) : $people->the_post(); ?>
		<?php  load_template( FOOPEOPLE_PATH.'includes/templates/person-listing-item.php', false );?>
<?php endwhile; ?>
	</ol>
<?php endif;
wp_reset_postdata();
?>
</div>
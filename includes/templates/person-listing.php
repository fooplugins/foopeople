<?php if ( !empty( $people ) ) {?>
<div id="foopeople" class="foopeople js-foopeople">

	<?php if ( $search) { ?>
	<div class="ppl_search-wrapper">
		<input id="ppl__search" type="search" class="ppl_search-field js-foopeople-search" placeholder="<?php _e('Search for people by name, team or skill...', 'foopeople'); ?>">
	</div>
	<?php } ?>

	<?php if ( !empty( $team ) ) { ?>
		<h2 class="ppl__heading"><?php _e('Showing all people in ', 'foopeople'); ?><?php echo ucwords($team); ?></h2>
	<?php } ?>

	<ol class="ppl_listing" data-ppl-columns="<?php echo $columns ?>">
	<?php foreach( $people as $person ) { ?>
		<?php foopeople_render_template( 'person-listing-item', array( 'person' => new foopeople_Person( $person ) ) ); ?>
	<?php } ?>
	</ol>
</div>
<?php }

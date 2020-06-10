<?php
$people = new WP_Query( array(
	'post_type' => array(
		FOOPEOPLE_CPT_PERSON,
	),
	'post_status' => array( // (string | array) - use post status. Retrieves posts by Post Status, default value i'publish'.
		'publish'
	),
	'posts_per_page' => -1,
	'orderby' => 'title'
) );
wp_reset_postdata();

?>


<div id="foopeople" class="foopeople js-foopeople">

	<div class="ppl_search-wrapper">
		<input id="ppl__search" type="search" class="ppl_search-field js-foopeople-search" placeholder="<?php _e('Search for people by name, team or skill...', 'foopeople'); ?>">
	</div>

	<h2 class="ppl__heading">
		<?php _e('Showing all people in ', 'foopeople'); ?>
		<?php echo '"'.ucwords($data['team']).'"'; ?>
	</h2>
<?php if ( $people->have_posts() ) : ?>
	<ol class="ppl_listing">
<?php while ( $people->have_posts() ) : $people->the_post(); ?>
		<?php
		// global $post;
		// $person = new FooPlugins\FooPeople\objects\Person($post);
		// echo foopeople_render_template('', 'person-listing-item', false, $person );?>
		<?php  load_template( FOOPEOPLE_PATH.'includes/templates/person-listing-item.php', false );?>
<?php endwhile; ?>
	</ol>
<?php endif; ?>
</div>
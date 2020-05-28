<?php
$instance = PacePeople_Plugin::get_instance();
$info = $instance->get_plugin_info();
$title = apply_filters( 'pacepeople_admin_help_title', sprintf( __( 'Welcome to %s %s', 'pacepeople' ), pacepeople_plugin_name(), $info['version'] ) );
$tagline = apply_filters( 'pacepeople_admin_help_tagline', sprintf( __( 'Thank you for choosing %s, the most intuitive and extensible people creation and management tool ever created for WordPress!', 'pacepeople' ), pacepeople_plugin_name() ) );
$link = apply_filters( 'pacepeople_admin_help_tagline_link', ' - <a href="http://foo.people" target="_blank">' . __( 'Visit our homepage', 'pacepeople' ) . '</a>' );
$show_foobot = apply_filters( 'pacepeople_admin_show_foobot', true );
$show_tabs = apply_filters( 'pacepeople_admin_help_show_tabs', true );
?>
<style>
	.about-wrap img.pacepeople-help-screenshot {
		float:right;
		margin-left: 20px;
	}

	.pacepeople-badge-foobot {
		position: absolute;
		top: 15px;
		right: 0;
		background:url(<?php echo PACEPEOPLE_URL; ?>assets/logo.png) no-repeat;
		width:200px;
		height:200px;
	}
	.feature-section h2 {
		margin-top: 0;
	}

	.about-wrap h2.nav-tab-wrapper {
		margin-bottom: 20px;
	}

</style>
<div class="wrap about-wrap">
	<h1><?php echo $title; ?></h1>
	<div class="about-text">
		<?php echo $tagline. $link; ?>
	</div>
	<?php if ( $show_foobot ) { ?>
	<div class="pacepeople-badge-foobot"></div>
	<?php } ?>
	<?php if ( $show_tabs ) { ?>
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab nav-tab-active" href="#">
			<?php _e( 'Getting Started', 'pacepeople' ); ?>
		</a>
		<a class="nav-tab" href="<?php echo esc_url( pacepeople_admin_extensions_url() ); ?>">
			<?php _e( 'Extensions', 'pacepeople' ); ?>
		</a>
		<a class="nav-tab" href="http://fooplugins.com">
			<?php _e( 'Other Plugins', 'pacepeople' ); ?>
		</a>
		<?php if ( current_user_can( 'activate_plugins' ) ) { ?>
		<a class="nav-tab" href="<?php echo esc_url( pacepeople_admin_systeminfo_url() ); ?>">
			<?php _e( 'System Info', 'pacepeople' ); ?>
		</a>
		<?php } ?>
	</h2>
	<?php } else { ?><hr /><?php } ?>
	<div class="changelog">

		<div class="feature-section">

			<img src="<?php echo PACEPEOPLE_URL . 'assets/screenshots/admin-edit-people.jpg'; ?>" class="pacepeople-help-screenshot"/>

			<h2><?php _e( 'Creating Your First People', 'pacepeople' );?></h2>

			<h4><?php printf( __( '1. <a href="%s">People &rarr; Add New</a>', 'pacepeople' ), esc_url ( admin_url( 'post-new.php?post_type=pacepeople' ) ) ); ?></h4>
			<p><?php _e( 'To create your first people, simply click the Add New button or click the Add People link in the menu.', 'pacepeople' ); ?></p>

			<h4><?php _e( '2. Add Media', 'pacepeople' );?></h4>
			<p><?php _e( 'Click the Add Media button and choose images from the media library to include in your people.', 'pacepeople' );?></p>

			<h4><?php _e( '3. Choose a Template', 'pacepeople' );?></h4>
			<p><?php _e( 'We have loads of awesome built-in people templates to choose from.', 'pacepeople' );?></p>

			<h4><?php _e( '4. Adjust Your Settings', 'pacepeople' );?></h4>
			<p><?php _e( 'There are tons of settings to help you customize the people to suit your needs.', 'pacepeople' );?></p>
		</div>
	</div>

	<?php do_action( 'pacepeople_admin_help_after_section_one' ); ?>

	<div class="changelog">

		<div class="feature-section">
			<img src="<?php echo PACEPEOPLE_URL . 'assets/screenshots/admin-insert-shortcode.jpg'; ?>" class="pacepeople-help-screenshot"/>

			<h2><?php _e( 'Show Off Your People', 'pacepeople' );?></h2>

			<h4><?php printf( __( 'The <em>[%s]</em> Short Code','pacepeople' ), pacepeople_people_shortcode_tag() );?></h4>
			<p><?php _e( 'Simply copy the shortcode code from the people listing page and paste it into your posts or pages.', 'pacepeople' );?></p>

			<h4><?php _e( 'Visual Editor Button', 'pacepeople' );?></h4>
			<p><?php printf( __( 'Or to make life even easier, you can insert a people using the Add %s button inside the WordPress visual editor.', 'pacepeople' ), pacepeople_plugin_name() );?></p>

			<h4><?php _e( 'Copy To Clipboard','pacepeople' );?></h4>
			<p><?php _e( 'We make your life easy! Just click the shortcodes and they get copied to your clipboard automatically. ', 'pacepeople' );?></p>

		</div>
	</div>

	<?php do_action( 'pacepeople_admin_help_after_section_two' ); ?>

</div>

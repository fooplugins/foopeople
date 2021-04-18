<?php
/*
* Customiser Options for FooPeople Plugin
*/

if ( ! class_exists( 'Customizer' ) ) {
    class Customizer {


        public function __construct() {
            add_action('customize_register', array( $this, 'customize_register' ) );
            add_action('after_setup_theme', array( $this, 'add_body_classes' ) );
            add_action('wp_head' , array( $this, 'add_head_styles' ) );
            add_action('admin_head' , array( $this, 'add_head_styles' ) );
            add_action('customize_controls_print_styles', array( $this, 'add_customizer_stylesheet' ) );
        }

        function add_customizer_stylesheet() {
            wp_register_style( 'foopeople_customizer_styles', plugin_dir_url(dirname( __FILE__ )) .  '../css/foopeople.customizer.min.css', array(), '' );
            wp_enqueue_style( 'foopeople_customizer_styles' );
        }

        function build_body_classes() {
            $body_class = ' / ';

            switch ( foopeople_get_setting('shadows') ) {
                case 'shadows':
                    $body_class .= 'ppl-shadows ';
                break;
                case 'no shadows':
                    $body_class .= 'ppl-no-shadows ';
                break;
                default:
                    $body_class .= 'ppl-shadows ';
                break;
            }

            switch ( foopeople_get_setting('rounded_corners') ) {
                case 'round':
                    $body_class .= 'ppl-round-corners ';
                break;
                case 'square':
                    $body_class .= 'ppl-square-corners ';
                break;
                default:
                    $body_class .= 'ppl-round-corners ';
                break;
            }

            switch ( foopeople_get_setting('portrait_style') ) {
                case 'rounded':
                    $body_class .= 'ppl-rounded-portraits ';
                break;
                case 'circle':
                    $body_class .= 'ppl-circle-portraits ';
                break;
                case 'square':
                    $body_class .= 'ppl-square-portrait ';
                break;
                default:
                    $body_class .= 'ppl-square-portrait ';
                break;
            }
            return $body_class;
        }

        function add_body_classes() {
            add_filter( 'admin_body_class', function($classes) {
                $classes .= $this->build_body_classes();
                return $classes.'/';
            } );
            add_filter( 'body_class', function( $classes ) {
                $body_class = $this->build_body_classes();
                return array_merge( $classes, array( $body_class.'/' ) );
            } );
        }

        function customize_register($wp_customize) {
            // Add Panels
            // ----------------------------------------------------------------------------
            $wp_customize->add_panel( 'ppl_panel', array(
                'priority'       => 1,
                'title'          => 'FooPeople',
                'description'    => 'FooPeople theme options'
            ) );


            // Add Sections
            // ----------------------------------------------------------------------------

            $wp_customize->add_section( 'ppl_section_listing', array(
                'priority' => 10,
                'title' => __('People Listing', 'foopeople'),
                'description' => '',
                'panel' => 'ppl_panel',
            ) );

            $wp_customize->add_section( 'ppl_section_portraits', array(
                'priority' => 20,
                'title' => __('People Portraits', 'foopeople'),
                'description' => '',
                'panel' => 'ppl_panel',
            ) );

            // $wp_customize->add_section( 'ppl_section_organogram', array(
            //     'priority' => 30,
            //     'title' => __('People Organograms', 'foopeople'),
            //     'description' => '',
            //     'panel' => 'ppl_panel',
            // ) );



            // Listing Columns
            // ----------------------------------------------------------------------------
            $wp_customize->add_setting(FOOPEOPLE_CUSTOMIZER_PREFIX.'[listing_columns]', array(
                'default'        => '2',
                'capability'     => 'edit_theme_options',
                'type'           => 'option',

            ));
            $wp_customize->add_control( 'ppl_control[listing_columns]', array(
                'settings' => FOOPEOPLE_CUSTOMIZER_PREFIX.'[listing_columns]',
                'label'   => __('Columns', 'foopeople'),
                'description'=> __( 'How many columns for the people listing', 'foopeople'),
                'section' => 'ppl_section_listing',
                'type'    => 'select',
                'choices'    => array(
                    '1' => __('1 Column', 'foopeople'),
                    '2' => __('2 Columns', 'foopeople'),
                    '3' => __('3 Columns', 'foopeople'),
                    '4' => __('4 Columns', 'foopeople'),
                ),
            ));


            // Rounded Corners
            // ----------------------------------------------------------------------------
            $wp_customize->add_setting(FOOPEOPLE_CUSTOMIZER_PREFIX.'[rounded_corners]', array(
                'capability' => 'edit_theme_options',
                'default'    => 'round',
                'type'       => 'option',
            ));

            $wp_customize->add_control('ppl_control[rounded_corners]', array(
                'label'      => __('Rounded Corners', 'foopeople'),
                'description'=> __( 'Corner style on FooPeople layout', 'foopeople'),
                'section'    => 'ppl_section_listing',
                'settings'   => FOOPEOPLE_CUSTOMIZER_PREFIX.'[rounded_corners]',
                'type'       => 'radio',
                'choices'    => array(
                    'round' => __('Rounded Corners', 'foopeople'),
                    'square' => __('Square Corners', 'foopeople'),
                ),
            ));





            // Portrait Border Color
            // ----------------------------------------------------------------------------

            $wp_customize->add_setting(FOOPEOPLE_CUSTOMIZER_PREFIX.'[portrait_border_color]', array(
                'default'           => 'ccc',
                'sanitize_callback' => 'sanitize_hex_color',
                'capability'        => 'edit_theme_options',
                'type'              => 'option',

            ));

            $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'ppl_control[portrait_border_color]', array(
                'label'    => __('Portrait Border Color', 'foopeople'),
                'section'  => 'ppl_section_portraits',
                'settings' => FOOPEOPLE_CUSTOMIZER_PREFIX.'[portrait_border_color]',
            )));

            // Portrait Border width
            // ----------------------------------------------------------------------------

            $wp_customize->add_setting(FOOPEOPLE_CUSTOMIZER_PREFIX.'[portrait_border_width]', array(
                'default'           => 1,
                'capability'        => 'edit_theme_options',
                'type'              => 'option',
            ));

            $wp_customize->add_control( 'ppl_control[portrait_border_width]', array(
                'type'        => 'range',
                'section'     => 'ppl_section_portraits',
                'label'       => __('Portrait Border Width', 'foopeople'),
                'settings'    => FOOPEOPLE_CUSTOMIZER_PREFIX.'[portrait_border_width]',
                'input_attrs' => array(
                    'min'   => 0,
                    'max'   => 6,
                    'step'  => 1,
                    'class' => 'ppl__range-control',
                    'style' => 'width: 100%',
                ),
            ) );


            // Portrait style
            // ----------------------------------------------------------------------------
            $wp_customize->add_setting(FOOPEOPLE_CUSTOMIZER_PREFIX.'[portrait_style]', array(
                'capability' => 'edit_theme_options',
                'default'    => 'square',
                'type'       => 'option'
            ));

            $wp_customize->add_control('ppl_control[portrait_style]', array(
                'label'      => __('Portraits', 'foopeople'),
                'description'=> __( 'Corner style on FooPeople portraits', 'foopeople'),
                'section'    => 'ppl_section_portraits',
                'settings'   => FOOPEOPLE_CUSTOMIZER_PREFIX.'[portrait_style]',
                'type'       => 'radio',
                'choices'    => array(
                    'circle' => __('Circle Portrait', 'foopeople'),
                    'rounded' => __('Round Corners Portrait', 'foopeople'),
                    'square' => __('Square Portrait', 'foopeople'),
                ),
            ));

            // Shadows
            // ----------------------------------------------------------------------------
            $wp_customize->add_setting(FOOPEOPLE_CUSTOMIZER_PREFIX.'[shadows]', array(
                'capability' => 'edit_theme_options',
                'default'    => 'shadows',
                'type'       => 'option'
            ));

            $wp_customize->add_control('ppl_control[shadows]', array(
                'label'      => __('Shadows', 'foopeople'),
                'description'=> __( 'Drop Shadows on FooPeople layout', 'foopeople'),
                'section'    => 'ppl_section_portraits',
                'settings'   => FOOPEOPLE_CUSTOMIZER_PREFIX.'[shadows]',
                'type'       => 'radio',
                'choices'    => array(
                    'shadows' => __('Shadows', 'foopeople'),
                    'no shadows' => __('No Shadows', 'foopeople'),
                ),
            ));





            // Organogram Line Color
            // ----------------------------------------------------------------------------
            $wp_customize->add_setting(FOOPEOPLE_CUSTOMIZER_PREFIX.'[organogram_line_color]', array(
                'default'           => 'ccc',
                'sanitize_callback' => 'sanitize_hex_color',
                'capability'        => 'edit_theme_options',
                'type'              => 'option',

            ));

            $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'ppl_control[organogram_line_color]', array(
                'label'    => __('Organogram Line Color', 'foopeople'),
                'section'  => 'ppl_section_organogram',
                'settings' => FOOPEOPLE_CUSTOMIZER_PREFIX.'[organogram_line_color]',
            )));

            // Organogram Expander Color
            // ----------------------------------------------------------------------------

            $wp_customize->add_setting(FOOPEOPLE_CUSTOMIZER_PREFIX.'[organogram_expander_color]', array(
                'default'           => 'ccc',
                'sanitize_callback' => 'sanitize_hex_color',
                'capability'        => 'edit_theme_options',
                'type'              => 'option',

            ));

            $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'ppl_control[organogram_expander_color]', array(
                'label'    => __('Organogram Expand Icon Color', 'foopeople'),
                'section'  => 'ppl_section_organogram',
                'settings' => FOOPEOPLE_CUSTOMIZER_PREFIX.'[organogram_expander_color]',
            )));

            // Organogram Line Weight
            // ----------------------------------------------------------------------------

            $wp_customize->add_setting(FOOPEOPLE_CUSTOMIZER_PREFIX.'[organogram_line_width]', array(
                'default'           => 1,
                'capability'        => 'edit_theme_options',
                'type'              => 'option',
            ));

            $wp_customize->add_control( 'ppl_control[organogram_line_width]', array(
                'type'        => 'range',
                'section'     => 'ppl_section_organogram',
                'label'       => __('Organogram Line Width', 'foopeople'),
                'description' => __('The line width for the organogram', 'foopeople'),
                'settings'    => FOOPEOPLE_CUSTOMIZER_PREFIX.'[organogram_line_width]',
                'input_attrs' => array(
                    'min'   => 2,
                    'max'   => 6,
                    'step'  => 2,
                    'class' => 'ppl__range-control',
                    'style' => 'width: 100%',
                ),
            ) );

        }

        function add_head_styles() {
            ?>
            <style id="ppl__styles">
                .top, .left, .right, .ppl__card_details  {
                    border-color: <?php echo foopeople_get_setting('organogram_line_color', '#e9ecef'); ?> !important;
                }
                .down {
                    background-color: <?php echo foopeople_get_setting('organogram_line_color', '#e9ecef'); ?> !important;
                }
                .ppl__card_details {
                    border-width : <?php echo foopeople_get_setting('organogram_line_width', '3').'px'; ?> !important;
                }
                .top {
                    border-top-width : <?php echo foopeople_get_setting('organogram_line_width', '3').'px'; ?> !important;
                }
                .left {
                    border-right-width : <?php echo foopeople_get_setting('organogram_line_width', '3').'px'; ?> !important;
                }
                .right {
                    border-left-width : <?php echo foopeople_get_setting('organogram_line_width', '3').'px'; ?> !important;
                }
                div.line {
                    width : <?php echo foopeople_get_setting('organogram_line_width', '3').'px'; ?> !important;
                }
                .ppl__expand-up:after,
                .ppl__expand-down:after {
                    color: <?php echo foopeople_get_setting('organogram_expander_color'); ?> !important;
                }
                .ppl__card_portrait_thumbnail {
                    border-width : <?php echo foopeople_get_setting('portrait_border_width', '3').'px'; ?> !important;
                    border-color: <?php echo foopeople_get_setting('portrait_border_color', '#e9ecef'); ?> !important;
                }
            </style>
            <?php
        }
    }
}
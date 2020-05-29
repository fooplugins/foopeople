<?php
namespace FooPlugins\FooPeople\Admin;

/**
 * The main Person class. It should hold no logic
 */
if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Person' ) ) {

	class Person {
		private $_post;

		/**
		 * constructor for a new instance
		 *
		 * @param $post
		 */
		function __construct($post = null) {
			$this->_post = null;
			$this->ID = 0;
			if ( isset( $post ) ) {
				$this->_post = $post;
				$this->ID = $post->ID;
				$this->slug = $post->post_name;
				$this->name = $post->post_title;
				$this->author = $post->post_author;
				$this->post_status = $post->post_status;
                $this->details = get_post_meta( $this->ID, FOOPEOPLE_PERSON_META_DETAILS, true );
				$this->search_index = get_post_meta( $this->ID, FOOPEOPLE_PERSON_META_SEARCH, true );
                $this->jobtitle = $this->get_details( 'jobtitle', '' );
				$this->email = $this->get_details( 'email', '' );
				$this->phonenumber = $this->get_details( 'phonenumber', '' );
			}
		}

        /**
         * Static function to load a Person instance by post id
         *
         * @param $post_id
         *
         * @return Person | boolean
         */
        public static function get_by_id( $post_id ) {
            $post = get_post( $post_id );
            if ( $post ) {
                $person = new self( $post );
            } else {
                $person = false;
            }
            return $person;
        }

        /**
         * Return the details field group
         * @return array
         */
        function get_details_field_group() {

            return array(
                'slug'   => FOOPEOPLE_PERSON_META_DETAILS,
                'container_id' => 'pacepeople_person_details',
                'sections' => array(
                    array(
                        'slug'  => 'personal',
                        'label' => __('Personal', FOOPEOPLE_SLUG),
                        'icon' => 'dashicons-admin-users',
                        'fields' => array(
                            array(
                                'id'       => 'firstname',
                                'title'    => __( 'First Name', FOOPEOPLE_SLUG ),
                                'required' => true,
                                'type'     => 'text',
                                'default'  => '',
                                'value'    => $this->get_details('firstname', ''),
                                'row_data'=> array(
                                    'placeholder' => __( 'The first name of the person.', FOOPEOPLE_SLUG ),
                                )
                            ),
                            array(
                                'id'       => 'surname',
                                'title'    => __( 'Last Name', FOOPEOPLE_SLUG ),
                                'required' => true,
                                'type'     => 'text',
                                'default'  => '',
                                'value'    => $this->get_details('surname', ''),
                                'row_data'=> array(
                                    'placeholder' => __( 'The last name/surname/family name of the person.', FOOPEOPLE_SLUG ),
                                )
                            ),
                            array(
                                'id'       => 'preferred',
                                'title'    => __( 'Preferred Name', FOOPEOPLE_SLUG ),
                                'desc'	   => __( 'You can override the full name for the person. Leave blank to default the full name to be "First Name + Last Name".', FOOPEOPLE_SLUG ),
                                'required' => false,
                                'type'     => 'text',
                                'default'  => '',
                                'value'    => $this->get_details('preferred', ''),
                                'row_data'=> array(
                                    'placeholder' => __( 'You can override what the full name for the person will be. Leave blank', FOOPEOPLE_SLUG ),
                                )
                            ),
                            array(
                                'id'       => 'jobtitle',
                                'title'    => __( 'Job Title', FOOPEOPLE_SLUG ),
                                'desc'	   => __( '', FOOPEOPLE_SLUG ),
                                'required' => true,
                                'type'     => 'text',
                                'default'  => '',
                                'value'    => $this->get_details('jobtitle', ''),
                                'row_data'=> array(
                                    'placeholder' => __( 'Job title', FOOPEOPLE_SLUG ),
                                )
                            )

                        )
					),
					array(
                        'slug'  => 'portrait',
                        'label' => __('Portrait', FOOPEOPLE_SLUG),
                        'icon' => 'dashicons-format-image',
                        'fields' => array()
                    ),
                    array(
                        'slug'  => 'contact',
                        'label' => __('Contact', FOOPEOPLE_SLUG),
                        'icon' => 'dashicons-phone',
                        'fields' => array(
                            array(
                                'id'       => 'email',
                                'title'    => __( 'Email', FOOPEOPLE_SLUG ),
                                'required' => true,
                                'type'     => 'text',
                                'default'  => '',
                                'value'    => $this->get_details('email', ''),
                            ),
                            array(
                                'id'       => 'phonenumber',
                                'title'    => __( 'Phone Number', FOOPEOPLE_SLUG ),
                                'required' => true,
                                'type'     => 'text',
                                'default'  => '',
                                'value'    => $this->get_details('phonenumber', ''),
                            ),
                        )
                    ),
                    array(
                        'slug'  => 'teams',
                        'label' => __('Teams', FOOPEOPLE_SLUG),
                        'icon' => 'dashicons-groups',
                        'fields' => array()
                    ),
                    array(
                        'slug'  => 'skills',
                        'label' => __('Skills', FOOPEOPLE_SLUG),
                        'icon' => 'dashicons-tag',
                        'fields' => array()
                    ),
                    array(
                        'slug'  => 'locations',
                        'label' => __('Locations', FOOPEOPLE_SLUG),
                        'icon' => 'dashicons-admin-site',
                        'fields' => array()
                    )
                )
            );
        }

		/**
		 * Checks if the person exists
		 * @return bool
		 */
		public function does_exist() {
			return $this->ID > 0;
		}

		/**
		 * Returns specific detail for a person
		 * @param      $key
		 * @param mixed $default
		 *
		 * @return mixed
		 */
		public function get_details( $key, $default = false ) {
			if ( !empty( $this->details ) && array_key_exists( $key, $this->details ) ) {
				return $this->details[$key];
			}

			return $default;
		}

		/**
		 * Builds up a search index
		 * @return string
		 */
		public function build_search_index() {
			$terms[] = $this->get_details('firstname', '');
			$terms[] = $this->get_details('surname', '');
			$terms[] = $this->get_details('preferred', '');
			$terms[] = $this->get_details('jobtitle', '');
			$search = implode( ' ', $terms );
			$search .= $this->department_list();
			$search .= $this->location_list();
			$search .= $this->skills_list();
			return strtolower($search);
		}

		/**
		 * Gets all departments for the person
		 *
		 * @return array
		 */
		public function departments() {
			$terms = get_the_terms( $this->ID, PACEPEOPLE_CT_DEPARTMENT );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$departments = array();

				foreach ($terms as $term) {
					$departments[] = $term->name;
				}

				return $departments;
			}
			return array();
		}

		/**
		 * Builds up a list of departments for the person
		 *
		 * @param string $glue
		 *
		 * @return string
		 */
		public function department_list( $glue = ' ') {
			return implode( $glue, $this->departments() );
		}

		/**
		 * Gets all locations for the person
		 *
		 * @return array
		 */
		public function locations() {
			$terms = get_the_terms( $this->ID, PACEPEOPLE_CT_LOCATION );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$locations = array();

				foreach ($terms as $term) {
					$locations[] = $term->name;
				}

				return $locations;
			}
			return array();
		}

		/**
		 * Builds up a list of locations for the person
		 *
		 * @param string $glue
		 *
		 * @return string
		 */
		public function location_list( $glue = ' ') {
			return implode( $glue, $this->locations() );
		}

		/**
		 * Gets all skills for the person

		 * @return array
		 */
		public function skills() {
			$terms = get_the_terms( $this->ID, PACEPEOPLE_CT_SKILLS );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$skills = array();

				foreach ($terms as $term) {
					$skills[] = $term->name;
				}

				return $skills;
			}
			return array();
		}

		/**
		 * Builds up a list of skills for the person
		 *
		 * @param string $glue
		 *
		 * @return string
		 */
		public function skills_list( $glue = ' ') {
			return implode( $glue, $this->skills() );
		}
	}
}
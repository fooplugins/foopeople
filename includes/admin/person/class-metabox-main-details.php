<?php

namespace FooPlugins\FooPeople\Admin\Person;

use FooPlugins\FooPeople\Admin\FooFields\Metabox;

if ( ! class_exists( 'FooPlugins\FooPeople\Admin\Person\MetaboxMainDetails' ) ) {

	class MetaboxMainDetails extends Metabox {

		function __construct() {
			parent::__construct(
				array(
					'manager'        => FOOPEOPLE_SLUG,
					'post_type'      => FOOPEOPLE_CPT_PERSON,
					'metabox_id'     => 'details',
					'metabox_title'  => __( 'Main Details', 'foopeople' ),
					'priority'       => 'high',
					'meta_key'       => FOOPEOPLE_META_PERSON_MAIN,
					'plugin_url'     => FOOPEOPLE_URL,
					'plugin_version' => FOOPEOPLE_VERSION
				)
			);
		}

		function get_tabs() {
			$employment_types = apply_filters( 'FooPlugins\FooPeople\Admin\Person\MainDetails\EmploymentTypes', array(
				'fulltime' => __( 'Fulltime', 'foopeople' ),
				'parttime' => __( 'Part Time', 'foopeople' ),
				'contractor' => __( 'Contractor', 'foopeople' ),
				'temporary' => __( 'Temporary', 'foopeople' ),
				'trainee' => __( 'Trainee', 'foopeople' ),
				'intern' => __( 'Intern', 'foopeople' ),
			) );

			return apply_filters( 'FooPlugins\FooPeople\Admin\Person\MainDetails\Fields',
				array(
					array(
						'id'     => 'work',
						'label'  => __( 'Work', 'foopeople' ),
						'icon'   => 'dashicons-businessman',
						'tabs'  => array(
							array(
								'id'     => 'work-general',
								'label'  => __( 'General', 'foopeople' ),
								'class'  => 'foofields-cols-2',
								'fields' => array(
									array(
										//'order' => 1,
										'id'       => 'work-general-main-heading',
										'label'    => __( 'Main Details', 'foopeople' ),
										'type'     => 'heading',
										'class'    => 'foofields-full-width',
									),
									array(
										//'order' => 2,
										'id'       => 'firstname',
										'label'    => __( 'First Name', 'foopeople' ),
										'required' => true,
										'type'     => 'text',
										'default'  => '',
										'placeholder' => __( 'The first name of the person.', 'foopeople' ),
										'class' => 'foofields-colspan-1',
										'search_index' => true,
									), //firstname
									array(
										//'order' => 3,
										'id'       => 'surname',
										'label'    => __( 'Last Name', 'foopeople' ),
										'required' => true,
										'type'     => 'text',
										'default'  => '',
										'placeholder' => __( 'The last name/surname/family name of the person.', 'foopeople' ),
										'class' => 'foofields-colspan-1',
										'search_index' => true,
									), //surname

									array(
										//'order' => 4,
										'id'       => 'workemail',
										'label'    => __( 'Work Email', 'foopeople' ),
										'required' => true,
										'type'     => 'text',
										'class' => 'foofields-colspan-1',
									), //workemail
									array(
										//'order' => 5,
										'id'       => 'workmobile',
										'label'    => __( 'Work Mobile', 'foopeople' ),
										'type'     => 'text',
										'class' => 'foofields-colspan-1',
									), //workmobile
									array(
										//'order' => 6,
										'id'       => 'location',
										'label'    => __( 'Location', 'foopeople' ),
										'desc'     => __( 'Select the location that the person is based in. You can also add a location if it does not exist.', 'foopeople' ),
										'type'     => 'selectize-multi',
										'create'   => true,
										'class' => 'foofields-colspan-1',
										'close_after_select' => true,
										'max_items' => 1,
										'binding' => array(
											'type' => 'taxonomy',
											'taxonomy' => FOOPEOPLE_CT_LOCATION,
											'sync_with_post' => true
										)
									), //locations

									array(
										//'order' => 7,
										'id'       => 'work-general-employment-heading',
										'label'    => __( 'Employment Details', 'foopeople' ),
										'type'     => 'heading',
										'class'    => 'foofields-full-width',
									),
									array(
										//'order' => 8,
										'id'       => 'employeenumber',
										'label'    => __( 'Employee Number', 'foopeople' ),
										'desc'     => __( 'A unique number that identifies the person in the company.', 'foopeople' ),
										'type'     => 'text',
										'default'  => '',
									), //employeenumber
									array(
										//'order' => 8,
										'id'       => 'employmenttype',

										'label'    => __( 'Employment Type', 'foopeople' ),
										'desc'     => __( '', 'foopeople' ),
										'type'     => 'select',
										'choices' => $employment_types,
										'default'  => 'fulltime',
									), //employmenttype

									array(
										//'order' => 9,
										'id'       => 'datejoined',
										'label'    => __( 'Joined Date', 'foopeople' ),
										'desc'     => __( 'The date the person joined the company. This is used to calculate length of service.', 'foopeople' ),
										'required' => true,
										'type'     => 'date',
										'min'     => '1970-01-01',
										'max'     => date("Y-m-d"),
										'default'  => '',
										'class'    => 'foofields-colspan-2',
										'after_input_render' => function( $field ) {
											$datejoined = $field->value();

											if ( $datejoined !== '' ) {
												$diff = abs(time() - strtotime( $datejoined ) );
												$years = floor($diff / (365*60*60*24));
												$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));

												echo '&nbsp;<strong>' . sprintf( __( '%d years, %d months', 'foopeople' ), $years, $months ) . '</strong>';
											}
										}
									), //datejoined

									array(
										//'order' => 10,
										'id'       => 'jobtitle',
										'label'    => __( 'Job Title', 'foopeople' ),
										'desc'     => __( 'The job title specific to this person. It could be the same as the Job Role, or you can customise it for this person.', 'foopeople' ),
										'required' => true,
										'type'     => 'text',
										'default'  => '',
										'placeholder' => __( 'Job title', 'foopeople' ),
									), //jobtitle
									array(
										//'order' => 11,
										'id'       => 'role',
										'label'    => __( 'Job Role', 'foopeople' ),
										'desc'     => __( 'A Job Role is a grouping of job titles, e.g. Developer, Executive, Engineer.', 'foopeople' ),
										'type'     => 'selectize-multi',
										'default'  => '',
										'create'   => true,
										'placeholder' => __( 'Start typing the name of the role', 'foopeople' ),
										'close_after_select' => true,
										'max_items' => 1,
										'binding' => array(
											'type' => 'post',
											'post_type' => FOOPEOPLE_CPT_ROLE,
										)
									), //role

									array(
										//'order' => 12,
										'id'       => 'team',
										'label'    => __( 'Team', 'foopeople' ),
										'type'     => 'selectize-multi',
										'desc'     => __( 'Select which team does the person belongs to. You can also add a team if it does not exist.', 'foopeople' ),
										'create'   => true,
										'close_after_select' => true,
										'max_items' => 1,
										'binding' => array(
											'type' => 'taxonomy',
											'taxonomy' => FOOPEOPLE_CT_TEAM,
											'sync_with_post' => true
										)
									), //team
									array(
										//'order' => 13,
										'id'       => 'manager',
										'label'    => __( 'Line Manager', 'foopeople' ),
										'desc'     => __( 'Who does this person report to?', 'foopeople' ),
										'type'     => 'selectize',
										'default'  => '',
										'placeholder' => __( 'Start typing the manager name', 'foopeople' ),
										'query' => array(
											'type' => 'post',
											'data' => FOOPEOPLE_CPT_PERSON
										)
									), //manager
									array(
										//'order' => 14,
										'id'       => 'work-general-skills-heading',
										'label'    => __( 'Skills', 'foopeople' ),
										'desc'     => __( 'Capture all the technical skills that you know this person has. For example C# or Javascript for a developer, or Business Management for a director.', 'foopeople' ),
										'type'     => 'heading',
										'class'    => 'foofields-full-width',
									),
									array(
										//'order' => 15,
										'id'       => 'skills',
										'label'    => __( 'Skills', 'foopeople' ),
										'type'     => 'selectize-multi',
										'create'   => true,
										'class'    => 'foofields-full-width',
										'binding' => array(
											'type' => 'taxonomy',
											'taxonomy' => FOOPEOPLE_CT_SKILL,
											'sync_with_post' => true
										)
									),

									array(
										'id'       => 'work-general-users-heading',
										//'order' => 16,
										'label'    => __( 'WordPress User', 'foopeople' ),
										'desc'     => __( 'You can link a WordPress user to this person.', 'foopeople' ),
										'type'     => 'heading',
										'class'    => 'foofields-full-width',
									),
									array(
										'id'       => 'user',
										//'order' => 17,
										'label'    => __( 'User', 'foopeople' ),
										'type'     => 'selectize',
										'placeholder' => __( 'Start typing the user name or email', 'foopeople' ),
										'query' => array(
											'type' => 'user'
										),
										'class'    => 'foofields-colspan-1',
										'binding' => array(
											'type' => 'taxonomy',
										)
									),
								)
							), //work general
							array(
								'id'     => 'work-compensation',
								'label'  => __( 'Compensation', 'foopeople' ),
								'class'  => 'foofields-cols-2',
								'fields' => array(
									array(
										'id'       => 'salary_currency',
										'label'    => __( 'Currency', 'foopeople' ),
										'type'     => 'selectize-multi',
										'close_after_select' => true,
										'max_items' => 1,
										'choices'  => array (
											array( 'value' => 'ALL', 'display' => 'Albania Lek (ALL)' ),
											array( 'value' => 'AFN', 'display' => 'Afghanistan Afghani (AFN)' ),
											array( 'value' => 'ARS', 'display' => 'Argentina Peso (ARS)' ),
											array( 'value' => 'AWG', 'display' => 'Aruba Guilder (AWG)' ),
											array( 'value' => 'AUD', 'display' => 'Australia Dollar (AUD)' ),
											array( 'value' => 'AZN', 'display' => 'Azerbaijan New Manat (AZN)' ),
											array( 'value' => 'BSD', 'display' => 'Bahamas Dollar (BSD)' ),
											array( 'value' => 'BBD', 'display' => 'Barbados Dollar (BBD)' ),
											array( 'value' => 'BDT', 'display' => 'Bangladeshi taka (BDT)' ),
											array( 'value' => 'BYR', 'display' => 'Belarus Ruble (BYR)' ),
											array( 'value' => 'BZD', 'display' => 'Belize Dollar (BZD)' ),
											array( 'value' => 'BMD', 'display' => 'Bermuda Dollar (BMD)' ),
											array( 'value' => 'BOB', 'display' => 'Bolivia Boliviano (BOB)' ),
											array( 'value' => 'BAM', 'display' => 'Bosnia and Herzegovina Convertible Marka (BAM)' ),
											array( 'value' => 'BWP', 'display' => 'Botswana Pula (BWP)' ),
											array( 'value' => 'BGN', 'display' => 'Bulgaria Lev (BGN)' ),
											array( 'value' => 'BRL', 'display' => 'Brazil Real (BRL)' ),
											array( 'value' => 'BND', 'display' => 'Brunei Darussalam Dollar (BND)' ),
											array( 'value' => 'KHR', 'display' => 'Cambodia Riel (KHR)' ),
											array( 'value' => 'CAD', 'display' => 'Canada Dollar (CAD)' ),
											array( 'value' => 'KYD', 'display' => 'Cayman Islands Dollar' ),
											array( 'value' => 'CLP', 'display' => 'Chile Peso' ),
											array( 'value' => 'CNY', 'display' => 'China Yuan Renminbi' ),
											array( 'value' => 'COP', 'display' => 'Colombia Peso' ),
											array( 'value' => 'CRC', 'display' => 'Costa Rica Colon' ),
											array( 'value' => 'HRK', 'display' => 'Croatia Kuna' ),
											array( 'value' => 'CUP', 'display' => 'Cuba Peso' ),
											array( 'value' => 'CZK', 'display' => 'Czech Republic Koruna' ),
											array( 'value' => 'DKK', 'display' => 'Denmark Krone' ),
											array( 'value' => 'DOP', 'display' => 'Dominican Republic Peso' ),
											array( 'value' => 'XCD', 'display' => 'East Caribbean Dollar' ),
											array( 'value' => 'EGP', 'display' => 'Egypt Pound' ),
											array( 'value' => 'SVC', 'display' => 'El Salvador Colon' ),
											array( 'value' => 'EEK', 'display' => 'Estonia Kroon' ),
											array( 'value' => 'EUR', 'display' => 'Euro Member Countries' ),
											array( 'value' => 'FKP', 'display' => 'Falkland Islands (Malvinas) Pound' ),
											array( 'value' => 'FJD', 'display' => 'Fiji Dollar' ),
											array( 'value' => 'GHC', 'display' => 'Ghana Cedis' ),
											array( 'value' => 'GIP', 'display' => 'Gibraltar Pound' ),
											array( 'value' => 'GTQ', 'display' => 'Guatemala Quetzal' ),
											array( 'value' => 'GGP', 'display' => 'Guernsey Pound' ),
											array( 'value' => 'GYD', 'display' => 'Guyana Dollar' ),
											array( 'value' => 'HNL', 'display' => 'Honduras Lempira' ),
											array( 'value' => 'HKD', 'display' => 'Hong Kong Dollar' ),
											array( 'value' => 'HUF', 'display' => 'Hungary Forint' ),
											array( 'value' => 'ISK', 'display' => 'Iceland Krona' ),
											array( 'value' => 'INR', 'display' => 'India Rupee' ),
											array( 'value' => 'IDR', 'display' => 'Indonesia Rupiah' ),
											array( 'value' => 'IRR', 'display' => 'Iran Rial' ),
											array( 'value' => 'IMP', 'display' => 'Isle of Man Pound' ),
											array( 'value' => 'ILS', 'display' => 'Israel Shekel' ),
											array( 'value' => 'JMD', 'display' => 'Jamaica Dollar' ),
											array( 'value' => 'JPY', 'display' => 'Japan Yen' ),
											array( 'value' => 'JEP', 'display' => 'Jersey Pound' ),
											array( 'value' => 'KZT', 'display' => 'Kazakhstan Tenge' ),
											array( 'value' => 'KPW', 'display' => 'Korea (North) Won' ),
											array( 'value' => 'KRW', 'display' => 'Korea (South) Won' ),
											array( 'value' => 'KGS', 'display' => 'Kyrgyzstan Som' ),
											array( 'value' => 'LAK', 'display' => 'Laos Kip' ),
											array( 'value' => 'LVL', 'display' => 'Latvia Lat' ),
											array( 'value' => 'LBP', 'display' => 'Lebanon Pound' ),
											array( 'value' => 'LRD', 'display' => 'Liberia Dollar' ),
											array( 'value' => 'LTL', 'display' => 'Lithuania Litas' ),
											array( 'value' => 'MKD', 'display' => 'Macedonia Denar' ),
											array( 'value' => 'MYR', 'display' => 'Malaysia Ringgit' ),
											array( 'value' => 'MUR', 'display' => 'Mauritius Rupee' ),
											array( 'value' => 'MXN', 'display' => 'Mexico Peso' ),
											array( 'value' => 'MNT', 'display' => 'Mongolia Tughrik' ),
											array( 'value' => 'MZN', 'display' => 'Mozambique Metical' ),
											array( 'value' => 'NAD', 'display' => 'Namibia Dollar' ),
											array( 'value' => 'NPR', 'display' => 'Nepal Rupee' ),
											array( 'value' => 'ANG', 'display' => 'Netherlands Antilles Guilder' ),
											array( 'value' => 'NZD', 'display' => 'New Zealand Dollar' ),
											array( 'value' => 'NIO', 'display' => 'Nicaragua Cordoba' ),
											array( 'value' => 'NGN', 'display' => 'Nigeria Naira' ),
											array( 'value' => 'NOK', 'display' => 'Norway Krone' ),
											array( 'value' => 'OMR', 'display' => 'Oman Rial' ),
											array( 'value' => 'PKR', 'display' => 'Pakistan Rupee' ),
											array( 'value' => 'PAB', 'display' => 'Panama Balboa' ),
											array( 'value' => 'PYG', 'display' => 'Paraguay Guarani' ),
											array( 'value' => 'PEN', 'display' => 'Peru Nuevo Sol' ),
											array( 'value' => 'PHP', 'display' => 'Philippines Peso' ),
											array( 'value' => 'PLN', 'display' => 'Poland Zloty' ),
											array( 'value' => 'QAR', 'display' => 'Qatar Riyal' ),
											array( 'value' => 'RON', 'display' => 'Romania New Leu' ),
											array( 'value' => 'RUB', 'display' => 'Russia Ruble' ),
											array( 'value' => 'SHP', 'display' => 'Saint Helena Pound' ),
											array( 'value' => 'SAR', 'display' => 'Saudi Arabia Riyal' ),
											array( 'value' => 'RSD', 'display' => 'Serbia Dinar' ),
											array( 'value' => 'SCR', 'display' => 'Seychelles Rupee' ),
											array( 'value' => 'SGD', 'display' => 'Singapore Dollar' ),
											array( 'value' => 'SBD', 'display' => 'Solomon Islands Dollar' ),
											array( 'value' => 'SOS', 'display' => 'Somalia Shilling' ),
											array( 'value' => 'ZAR', 'display' => 'South Africa Rand' ),
											array( 'value' => 'LKR', 'display' => 'Sri Lanka Rupee' ),
											array( 'value' => 'SEK', 'display' => 'Sweden Krona' ),
											array( 'value' => 'CHF', 'display' => 'Switzerland Franc' ),
											array( 'value' => 'SRD', 'display' => 'Suriname Dollar' ),
											array( 'value' => 'SYP', 'display' => 'Syria Pound' ),
											array( 'value' => 'TWD', 'display' => 'Taiwan New Dollar' ),
											array( 'value' => 'THB', 'display' => 'Thailand Baht' ),
											array( 'value' => 'TTD', 'display' => 'Trinidad and Tobago Dollar' ),
											array( 'value' => 'TRY', 'display' => 'Turkey Lira' ),
											array( 'value' => 'TRL', 'display' => 'Turkey Lira' ),
											array( 'value' => 'TVD', 'display' => 'Tuvalu Dollar' ),
											array( 'value' => 'UAH', 'display' => 'Ukraine Hryvna' ),
											array( 'value' => 'GBP', 'display' => 'United Kingdom Pound' ),
											array( 'value' => 'USD', 'display' => 'United States Dollar' ),
											array( 'value' => 'UYU', 'display' => 'Uruguay Peso' ),
											array( 'value' => 'UZS', 'display' => 'Uzbekistan Som' ),
											array( 'value' => 'VEF', 'display' => 'Venezuela Bolivar' ),
											array( 'value' => 'VND', 'display' => 'Viet Nam Dong' ),
											array( 'value' => 'YER', 'display' => 'Yemen Rial' ),
											array( 'value' => 'ZWD', 'display' => 'Zimbabwe Dollar' ),
										)
									), //salary_currency
									array(
										'id'       => 'salary',
										'label'    => __( 'Salary', 'foopeople' ),
										'type'     => 'number',
										'default'  => '',
									), //salary
								)
							), //work compensation
							array(
								'id'     => 'contactwork',
								'label'  => __( 'Contact Details', 'foopeople' ),
								'fields' => array(
									array(
										'id'       => 'worklandline',
										'label'    => __( 'Landline', 'foopeople' ),
										'type'     => 'text',
									),
									array(
										'id'       => 'workslack',
										'label'    => __( 'Slack', 'foopeople' ),
										'type'     => 'text',
										'placeholder'  => 'name.slack.com',
									),
									array(
										'id'       => 'workteams',
										'label'    => __( 'Teams', 'foopeople' ),
										'type'     => 'text',
										'placeholder'  => 'teams.microsoft.com/name',
									),
									array(
										'id'       => 'workskype',
										'label'    => __( 'Skype', 'foopeople' ),
										'type'     => 'text',
										'placeholder'  => '@name',
									),
									array(
										'id'       => 'worktwitter',
										'label'    => __( 'Twitter', 'foopeople' ),
										'type'     => 'text',
										'placeholder'  => '@name',
									),
									array(
										'id'       => 'workfacebook',
										'label'    => __( 'Facebook', 'foopeople' ),
										'type'     => 'text',
										'placeholder'  => 'facebook.com/name',
									),
								)
							), //work contact
						)
					), //work

					array(
						'id'     => 'personal',
						'label'  => __( 'Personal', 'foopeople' ),
						'icon'   => 'dashicons-admin-users',
						'tabs'  => array(
							array(
								'id'     => 'personal-general',
								'label'  => __( 'General', 'foopeople' ),
								'class'  => 'foofields-cols-2',
								'fields' => array(
									array(
										'id'       => 'dob',
										'label'    => __( 'Date of Birth', 'foopeople' ),
										'required' => true,
										'type'     => 'date',
										'min'     => '1950-01-01',
										'max'     => date("Y-m-d"),
									), //DOB
									array(
										'id'       => 'preferred',
										'label'    => __( 'Preferred Name', 'foopeople' ),
										'desc'     => __( 'You can override the first name for the person. This could be a nickname or a shortened name.', 'foopeople' ),
										'required' => false,
										'type'     => 'text',
										'default'  => '',
										'placeholder' => __( 'This could be a nickname or a shortened name', 'foopeople' ),
									), //preferred name

									array(
										'id'       => 'gender',
										'label'    => __( 'Gender', 'foopeople' ),
										'desc'     => __( '', 'foopeople' ),
										'type'     => 'radiolist',
										'choices' => array(
											'male' => __( 'Male', 'foopeople' ),
											'female' => __( 'Female', 'foopeople' ),
											'other' => __( 'Other', 'foopeople' ),
										)
									), //gender
									array(
										'id'       => 'ethnicity',
										'label'    => __( 'Ethnicity', 'foopeople' ),
										'desc'     => __( '', 'foopeople' ),
										'required' => false,
										'type'     => 'text',
										'default'  => ''
									), //ethnicity

									array(
										'id'       => 'maritalstatus',
										'label'    => __( 'Marital Status', 'foopeople' ),
										'desc'     => __( '', 'foopeople' ),
										'type'     => 'radiolist',
										'choices' => array(
											'single' => __( 'Single', 'foopeople' ),
											'married' => __( 'Married', 'foopeople' ),
											'divorced' => __( 'Divorced', 'foopeople' ),
											'widowed' => __( 'Widowed', 'foopeople' ),
										)
									), //maritalstatus
									array(
										'id'       => 'religion',
										'label'    => __( 'Religion', 'foopeople' ),
										'desc'     => __( '', 'foopeople' ),
										'required' => false,
										'type'     => 'text',
									), //religion

									array(
										'id'       => 'timezone',
										'label'    => __( 'Timezone', 'foopeople' ),
										'desc'     => __( '', 'foopeople' ),
										'type'     => 'select',
										'choices' => array(
											'GMT'		=> __( 'GMT' , 'foopeople' ),
											'UTC-12'	=> __( 'UTC-12' , 'foopeople' ),
											'UTC-11.5'	=> __( 'UTC-11:30' , 'foopeople' ),
											'UTC-11'	=> __( 'UTC-11' , 'foopeople' ),
											'UTC-10.5'	=> __( 'UTC-10:30' , 'foopeople' ),
											'UTC-10'	=> __( 'UTC-10' , 'foopeople' ),
											'UTC-9.5'	=> __( 'UTC-9:30' , 'foopeople' ),
											'UTC-9'		=> __( 'UTC-9' , 'foopeople' ),
											'UTC-8.5'	=> __( 'UTC-8:30' , 'foopeople' ),
											'UTC-8'		=> __( 'UTC-8' , 'foopeople' ),
											'UTC-7.5'	=> __( 'UTC-7:30' , 'foopeople' ),
											'UTC-7'		=> __( 'UTC-7' , 'foopeople' ),
											'UTC-6.5'	=> __( 'UTC-6:30' , 'foopeople' ),
											'UTC-6'		=> __( 'UTC-6' , 'foopeople' ),
											'UTC-5.5'	=> __( 'UTC-5:30' , 'foopeople' ),
											'UTC-5'		=> __( 'UTC-5' , 'foopeople' ),
											'UTC-4.5'	=> __( 'UTC-4:30' , 'foopeople' ),
											'UTC-4'		=> __( 'UTC-4' , 'foopeople' ),
											'UTC-3.5'	=> __( 'UTC-3:30' , 'foopeople' ),
											'UTC-3'		=> __( 'UTC-3' , 'foopeople' ),
											'UTC-2.5'	=> __( 'UTC-2:30' , 'foopeople' ),
											'UTC-2'		=> __( 'UTC-2' , 'foopeople' ),
											'UTC-1.5'	=> __( 'UTC-1:30' , 'foopeople' ),
											'UTC-1'		=> __( 'UTC-1' , 'foopeople' ),
											'UTC-0.5'	=> __( 'UTC-0:30' , 'foopeople' ),
											'UTC+0'		=> __( 'UTC+0' , 'foopeople' ),
											'UTC+0.5'	=> __( 'UTC+0:30' , 'foopeople' ),
											'UTC+1'		=> __( 'UTC+1' , 'foopeople' ),
											'UTC+1.5'	=> __( 'UTC+1:30' , 'foopeople' ),
											'UTC+2'		=> __( 'UTC+2' , 'foopeople' ),
											'UTC+2.5'	=> __( 'UTC+2:30' , 'foopeople' ),
											'UTC+3'		=> __( 'UTC+3' , 'foopeople' ),
											'UTC+3.5'	=> __( 'UTC+3:30' , 'foopeople' ),
											'UTC+4'		=> __( 'UTC+4' , 'foopeople' ),
											'UTC+4.5'	=> __( 'UTC+4:30' , 'foopeople' ),
											'UTC+5'		=> __( 'UTC+5' , 'foopeople' ),
											'UTC+5.5'	=> __( 'UTC+5:30' , 'foopeople' ),
											'UTC+5.75'	=> __( 'UTC+5:45' , 'foopeople' ),
											'UTC+6'		=> __( 'UTC+6' , 'foopeople' ),
											'UTC+6.5'	=> __( 'UTC+6:30' , 'foopeople' ),
											'UTC+7'		=> __( 'UTC+7' , 'foopeople' ),
											'UTC+7.5'	=> __( 'UTC+7:30' , 'foopeople' ),
											'UTC+8'		=> __( 'UTC+8' , 'foopeople' ),
											'UTC+8.5'	=> __( 'UTC+8:30' , 'foopeople' ),
											'UTC+8.75'	=> __( 'UTC+8:45' , 'foopeople' ),
											'UTC+9'		=> __( 'UTC+9' , 'foopeople' ),
											'UTC+9.5'	=> __( 'UTC+9:30' , 'foopeople' ),
											'UTC+10'	=> __( 'UTC+10' , 'foopeople' ),
											'UTC+10.5'	=> __( 'UTC+10:30' , 'foopeople' ),
											'UTC+11'	=> __( 'UTC+11' , 'foopeople' ),
											'UTC+11.5'	=> __( 'UTC+11:30' , 'foopeople' ),
											'UTC+12'	=> __( 'UTC+12' , 'foopeople' ),
											'UTC+12.75'	=> __( 'UTC+12:45' , 'foopeople' ),
											'UTC+13'	=> __( 'UTC+13' , 'foopeople' ),
											'UTC+13.75'	=> __( 'UTC+13:45' , 'foopeople' ),
											'UTC+14'	=> __( 'UTC+14' , 'foopeople' ),
										)
									), //timezone
									array(
										'id'       => 'nationality',
										'label'    => __( 'Nationality', 'foopeople' ),
										'desc'     => __( '', 'foopeople' ),
										'type'     => 'select',
										'choices' => array(
											'Afghanistan' => __( 'Afghanistan', 'foopeople' ),
											'Albania' => __( 'Albania', 'foopeople' ),
											'Algeria' => __( 'Algeria', 'foopeople' ),
											'Andorra' => __( 'Andorra', 'foopeople' ),
											'Angola' => __( 'Angola', 'foopeople' ),
											'Antigua & Deps' => __( 'Antigua & Deps', 'foopeople' ),
											'Argentina' => __( 'Argentina', 'foopeople' ),
											'Armenia' => __( 'Armenia', 'foopeople' ),
											'Australia' => __( 'Australia', 'foopeople' ),
											'Austria' => __( 'Austria', 'foopeople' ),
											'Azerbaijan' => __( 'Azerbaijan', 'foopeople' ),
											'Bahamas' => __( 'Bahamas', 'foopeople' ),
											'Bahrain' => __( 'Bahrain', 'foopeople' ),
											'Bangladesh' => __( 'Bangladesh', 'foopeople' ),
											'Barbados' => __( 'Barbados', 'foopeople' ),
											'Belarus' => __( 'Belarus', 'foopeople' ),
											'Belgium' => __( 'Belgium', 'foopeople' ),
											'Belize' => __( 'Belize', 'foopeople' ),
											'Benin' => __( 'Benin', 'foopeople' ),
											'Bhutan' => __( 'Bhutan', 'foopeople' ),
											'Bolivia' => __( 'Bolivia', 'foopeople' ),
											'Bosnia Herzegovina' => __( 'Bosnia Herzegovina', 'foopeople' ),
											'Botswana' => __( 'Botswana', 'foopeople' ),
											'Brazil' => __( 'Brazil', 'foopeople' ),
											'Brunei' => __( 'Brunei', 'foopeople' ),
											'Bulgaria' => __( 'Bulgaria', 'foopeople' ),
											'Burkina' => __( 'Burkina', 'foopeople' ),
											'Burundi' => __( 'Burundi', 'foopeople' ),
											'Cambodia' => __( 'Cambodia', 'foopeople' ),
											'Cameroon' => __( 'Cameroon', 'foopeople' ),
											'Canada' => __( 'Canada', 'foopeople' ),
											'Cape Verde' => __( 'Cape Verde', 'foopeople' ),
											'Central African Rep' => __( 'Central African Rep', 'foopeople' ),
											'Chad' => __( 'Chad', 'foopeople' ),
											'Chile' => __( 'Chile', 'foopeople' ),
											'China' => __( 'China', 'foopeople' ),
											'Colombia' => __( 'Colombia', 'foopeople' ),
											'Comoros' => __( 'Comoros', 'foopeople' ),
											'Congo' => __( 'Congo', 'foopeople' ),
											'Congo {Democratic Rep}' => __( 'Congo {Democratic Rep}', 'foopeople' ),
											'Costa Rica' => __( 'Costa Rica', 'foopeople' ),
											'Croatia' => __( 'Croatia', 'foopeople' ),
											'Cuba' => __( 'Cuba', 'foopeople' ),
											'Cyprus' => __( 'Cyprus', 'foopeople' ),
											'Czech Republic' => __( 'Czech Republic', 'foopeople' ),
											'Denmark' => __( 'Denmark', 'foopeople' ),
											'Djibouti' => __( 'Djibouti', 'foopeople' ),
											'Dominica' => __( 'Dominica', 'foopeople' ),
											'Dominican Republic' => __( 'Dominican Republic', 'foopeople' ),
											'East Timor' => __( 'East Timor', 'foopeople' ),
											'Ecuador' => __( 'Ecuador', 'foopeople' ),
											'Egypt' => __( 'Egypt', 'foopeople' ),
											'El Salvador' => __( 'El Salvador', 'foopeople' ),
											'Equatorial Guinea' => __( 'Equatorial Guinea', 'foopeople' ),
											'Eritrea' => __( 'Eritrea', 'foopeople' ),
											'Estonia' => __( 'Estonia', 'foopeople' ),
											'Ethiopia' => __( 'Ethiopia', 'foopeople' ),
											'Fiji' => __( 'Fiji', 'foopeople' ),
											'Finland' => __( 'Finland', 'foopeople' ),
											'France' => __( 'France', 'foopeople' ),
											'Gabon' => __( 'Gabon', 'foopeople' ),
											'Gambia' => __( 'Gambia', 'foopeople' ),
											'Georgia' => __( 'Georgia', 'foopeople' ),
											'Germany' => __( 'Germany', 'foopeople' ),
											'Ghana' => __( 'Ghana', 'foopeople' ),
											'Greece' => __( 'Greece', 'foopeople' ),
											'Grenada' => __( 'Grenada', 'foopeople' ),
											'Guatemala' => __( 'Guatemala', 'foopeople' ),
											'Guinea' => __( 'Guinea', 'foopeople' ),
											'Guinea-Bissau' => __( 'Guinea-Bissau', 'foopeople' ),
											'Guyana' => __( 'Guyana', 'foopeople' ),
											'Haiti' => __( 'Haiti', 'foopeople' ),
											'Honduras' => __( 'Honduras', 'foopeople' ),
											'Hungary' => __( 'Hungary', 'foopeople' ),
											'Iceland' => __( 'Iceland', 'foopeople' ),
											'India' => __( 'India', 'foopeople' ),
											'Indonesia' => __( 'Indonesia', 'foopeople' ),
											'Iran' => __( 'Iran', 'foopeople' ),
											'Iraq' => __( 'Iraq', 'foopeople' ),
											'Ireland {Republic}' => __( 'Ireland {Republic}', 'foopeople' ),
											'Israel' => __( 'Israel', 'foopeople' ),
											'Italy' => __( 'Italy', 'foopeople' ),
											'Ivory Coast' => __( 'Ivory Coast', 'foopeople' ),
											'Jamaica' => __( 'Jamaica', 'foopeople' ),
											'Japan' => __( 'Japan', 'foopeople' ),
											'Jordan' => __( 'Jordan', 'foopeople' ),
											'Kazakhstan' => __( 'Kazakhstan', 'foopeople' ),
											'Kenya' => __( 'Kenya', 'foopeople' ),
											'Kiribati' => __( 'Kiribati', 'foopeople' ),
											'Korea North' => __( 'Korea North', 'foopeople' ),
											'Korea South' => __( 'Korea South', 'foopeople' ),
											'Kosovo' => __( 'Kosovo', 'foopeople' ),
											'Kuwait' => __( 'Kuwait', 'foopeople' ),
											'Kyrgyzstan' => __( 'Kyrgyzstan', 'foopeople' ),
											'Laos' => __( 'Laos', 'foopeople' ),
											'Latvia' => __( 'Latvia', 'foopeople' ),
											'Lebanon' => __( 'Lebanon', 'foopeople' ),
											'Lesotho' => __( 'Lesotho', 'foopeople' ),
											'Liberia' => __( 'Liberia', 'foopeople' ),
											'Libya' => __( 'Libya', 'foopeople' ),
											'Liechtenstein' => __( 'Liechtenstein', 'foopeople' ),
											'Lithuania' => __( 'Lithuania', 'foopeople' ),
											'Luxembourg' => __( 'Luxembourg', 'foopeople' ),
											'Macedonia' => __( 'Macedonia', 'foopeople' ),
											'Madagascar' => __( 'Madagascar', 'foopeople' ),
											'Malawi' => __( 'Malawi', 'foopeople' ),
											'Malaysia' => __( 'Malaysia', 'foopeople' ),
											'Maldives' => __( 'Maldives', 'foopeople' ),
											'Mali' => __( 'Mali', 'foopeople' ),
											'Malta' => __( 'Malta', 'foopeople' ),
											'Marshall Islands' => __( 'Marshall Islands', 'foopeople' ),
											'Mauritania' => __( 'Mauritania', 'foopeople' ),
											'Mauritius' => __( 'Mauritius', 'foopeople' ),
											'Mexico' => __( 'Mexico', 'foopeople' ),
											'Micronesia' => __( 'Micronesia', 'foopeople' ),
											'Moldova' => __( 'Moldova', 'foopeople' ),
											'Monaco' => __( 'Monaco', 'foopeople' ),
											'Mongolia' => __( 'Mongolia', 'foopeople' ),
											'Montenegro' => __( 'Montenegro', 'foopeople' ),
											'Morocco' => __( 'Morocco', 'foopeople' ),
											'Mozambique' => __( 'Mozambique', 'foopeople' ),
											'Myanmar, {Burma}' => __( 'Myanmar, {Burma}', 'foopeople' ),
											'Namibia' => __( 'Namibia', 'foopeople' ),
											'Nauru' => __( 'Nauru', 'foopeople' ),
											'Nepal' => __( 'Nepal', 'foopeople' ),
											'Netherlands' => __( 'Netherlands', 'foopeople' ),
											'New Zealand' => __( 'New Zealand', 'foopeople' ),
											'Nicaragua' => __( 'Nicaragua', 'foopeople' ),
											'Niger' => __( 'Niger', 'foopeople' ),
											'Nigeria' => __( 'Nigeria', 'foopeople' ),
											'Norway' => __( 'Norway', 'foopeople' ),
											'Oman' => __( 'Oman', 'foopeople' ),
											'Pakistan' => __( 'Pakistan', 'foopeople' ),
											'Palau' => __( 'Palau', 'foopeople' ),
											'Panama' => __( 'Panama', 'foopeople' ),
											'Papua New Guinea' => __( 'Papua New Guinea', 'foopeople' ),
											'Paraguay' => __( 'Paraguay', 'foopeople' ),
											'Peru' => __( 'Peru', 'foopeople' ),
											'Philippines' => __( 'Philippines', 'foopeople' ),
											'Poland' => __( 'Poland', 'foopeople' ),
											'Portugal' => __( 'Portugal', 'foopeople' ),
											'Qatar' => __( 'Qatar', 'foopeople' ),
											'Romania' => __( 'Romania', 'foopeople' ),
											'Russian Federation' => __( 'Russian Federation', 'foopeople' ),
											'Rwanda' => __( 'Rwanda', 'foopeople' ),
											'St Kitts & Nevis' => __( 'St Kitts & Nevis', 'foopeople' ),
											'St Lucia' => __( 'St Lucia', 'foopeople' ),
											'Saint Vincent & the Grenadines' => __( 'Saint Vincent & the Grenadines', 'foopeople' ),
											'Samoa' => __( 'Samoa', 'foopeople' ),
											'San Marino' => __( 'San Marino', 'foopeople' ),
											'Sao Tome & Principe' => __( 'Sao Tome & Principe', 'foopeople' ),
											'Saudi Arabia' => __( 'Saudi Arabia', 'foopeople' ),
											'Senegal' => __( 'Senegal', 'foopeople' ),
											'Serbia' => __( 'Serbia', 'foopeople' ),
											'Seychelles' => __( 'Seychelles', 'foopeople' ),
											'Sierra Leone' => __( 'Sierra Leone', 'foopeople' ),
											'Singapore' => __( 'Singapore', 'foopeople' ),
											'Slovakia' => __( 'Slovakia', 'foopeople' ),
											'Slovenia' => __( 'Slovenia', 'foopeople' ),
											'Solomon Islands' => __( 'Solomon Islands', 'foopeople' ),
											'Somalia' => __( 'Somalia', 'foopeople' ),
											'South Africa' => __( 'South Africa', 'foopeople' ),
											'South Sudan' => __( 'South Sudan', 'foopeople' ),
											'Spain' => __( 'Spain', 'foopeople' ),
											'Sri Lanka' => __( 'Sri Lanka', 'foopeople' ),
											'Sudan' => __( 'Sudan', 'foopeople' ),
											'Suriname' => __( 'Suriname', 'foopeople' ),
											'Swaziland' => __( 'Swaziland', 'foopeople' ),
											'Sweden' => __( 'Sweden', 'foopeople' ),
											'Switzerland' => __( 'Switzerland', 'foopeople' ),
											'Syria' => __( 'Syria', 'foopeople' ),
											'Taiwan' => __( 'Taiwan', 'foopeople' ),
											'Tajikistan' => __( 'Tajikistan', 'foopeople' ),
											'Tanzania' => __( 'Tanzania', 'foopeople' ),
											'Thailand' => __( 'Thailand', 'foopeople' ),
											'Togo' => __( 'Togo', 'foopeople' ),
											'Tonga' => __( 'Tonga', 'foopeople' ),
											'Trinidad & Tobago' => __( 'Trinidad & Tobago', 'foopeople' ),
											'Tunisia' => __( 'Tunisia', 'foopeople' ),
											'Turkey' => __( 'Turkey', 'foopeople' ),
											'Turkmenistan' => __( 'Turkmenistan', 'foopeople' ),
											'Tuvalu' => __( 'Tuvalu', 'foopeople' ),
											'Uganda' => __( 'Uganda', 'foopeople' ),
											'Ukraine' => __( 'Ukraine', 'foopeople' ),
											'United Arab Emirates' => __( 'United Arab Emirates', 'foopeople' ),
											'United Kingdom' => __( 'United Kingdom', 'foopeople' ),
											'United States' => __( 'United States', 'foopeople' ),
											'Uruguay' => __( 'Uruguay', 'foopeople' ),
											'Uzbekistan' => __( 'Uzbekistan', 'foopeople' ),
											'Vanuatu' => __( 'Vanuatu', 'foopeople' ),
											'Vatican City' => __( 'Vatican City', 'foopeople' ),
											'Venezuela' => __( 'Venezuela', 'foopeople' ),
											'Vietnam' => __( 'Vietnam', 'foopeople' ),
											'Yemen' => __( 'Yemen', 'foopeople' ),
											'Zambia' => __( 'Zambia', 'foopeople' ),
											'Zimbabwe' => __( 'Zimbabwe', 'foopeople' ),
										)
									), //nationality

									array(
										'id'       => 'idnumber',
										'label'    => __( 'ID Number / Social Security Number', 'foopeople' ),
										'desc'     => __( '', 'foopeople' ),
										'type'     => 'text',
										'default'  => '',
									), //idnumber
								)
							), //general
							array(
								'id'     => 'personal-contact',
								'label'  => __( 'Contact Details', 'foopeople' ),
								'fields' => array(
									array(
										'id'       => 'personalemail',
										'label'    => __( 'Email', 'foopeople' ),
										'type'     => 'text',
									),
									array(
										'id'       => 'personalmobile',
										'label'    => __( 'Mobile Number', 'foopeople' ),
										'type'     => 'text',
									),
									array(
										'id'       => 'personalskype',
										'label'    => __( 'Skype', 'foopeople' ),
										'type'     => 'text',
										'placeholder'  => '@name',
									),
									array(
										'id'       => 'personaltwitter',
										'label'    => __( 'Twitter', 'foopeople' ),
										'type'     => 'text',
										'placeholder'  => '@name',
									),
									array(
										'id'       => 'personalfacebook',
										'label'    => __( 'Facebook', 'foopeople' ),
										'type'     => 'text',
										'placeholder'  => 'facebook.com/name',
									),
									array(
										'id'       => 'personallinkedin',
										'label'    => __( 'LinkedIn', 'foopeople' ),
										'type'     => 'text',
										'placeholder'  => 'linkedin.com/in/name',
									),
								)
							), //contact
							array(
								'id'     => 'personal-addresses',
								'label'  => __( 'Addresses', 'foopeople' ),
								'fields' => array(
									array(
										'id' => 'addresses',
										'type' => 'repeater',
										'add_button_text'   => __( 'Add Address', 'foopeople' ),
										'fields'   => array(
											array(
												'id' => 'index',
												'type' => 'repeater-index'
											),
											array(
												'id'       => 'address',
												'label'    => __( 'Address', 'foopeople' ),
												'type'     => 'textarea',
											),
											array(
												'id'       => 'postcode',
												'label'    => __( 'Post Code', 'foopeople' ),
												'type'     => 'text',
											),
											array(
												'id'       => 'address_type',
												'label'    => __( 'Type', 'foopeople' ),
												'type'     => 'select',
												'choices' => array(
													'postal' => __( 'Postal', 'foopeople' ),
													'residential' => __( 'Residential', 'foopeople' ),
												)
											),
											array(
												'id'       => 'manage',
												'type'     => 'repeater-delete',
											),
										)
									)
								)
							), //addresses
							array(
								'id'     => 'personal-medical',
								'label'  => __( 'Medical', 'foopeople' ),
								'fields' => array(
									array(
										'id'       => 'nextofkin',
										'label'    => __( 'Next of Kin details (name, contact details)', 'foopeople' ),
										'type'     => 'textarea',
									),
									array(
										'id'       => 'doctordetails',
										'label'    => __( 'Doctors details (name, contact details)', 'foopeople' ),
										'type'     => 'textarea',
									),
									array(
										'id'       => 'bloodtype',
										'label'    => __( 'Blood Type', 'foopeople' ),
										'type'     => 'select',
										'choices' => array(
											'A+' => __( 'A+', 'foopeople' ),
											'A-' => __( 'A-', 'foopeople' ),
											'B+' => __( 'B+', 'foopeople' ),
											'B-' => __( 'B-', 'foopeople' ),
											'O+' => __( 'O+', 'foopeople' ),
											'O-' => __( 'O-', 'foopeople' ),
											'AB+' => __( 'AB+', 'foopeople' ),
											'AB-' => __( 'AB-', 'foopeople' ),
										)
									),
									array(
										'id'       => 'allergies',
										'label'    => __( 'Allergies', 'foopeople' ),
										'type'     => 'textarea',
									),
								)
							), //medical
							array(
								'id'     => 'personal-dependants',
								'label'  => __( 'Dependants', 'foopeople' ),
								'fields' => array(
									array(
										'id' => 'dependants',
										'type' => 'repeater',
										'add_button_text'   => __( 'Add Dependant', 'foopeople' ),
										'fields'   => array(
											array(
												'id' => 'index',
												'type' => 'repeater-index'
											),
											array(
												'id'       => 'text',
												'label'    => __( 'Full Name', 'foopeople' ),
												'type'     => 'text',
											),
											array(
												'id'       => 'relationship',
												'label'    => __( 'Relationship', 'foopeople' ),
												'type'     => 'select',
												'choices' => array(
													'spouse' => __( 'Spouse', 'foopeople' ),
													'child' => __( 'Child', 'foopeople' ),
													'father' => __( 'Father', 'foopeople' ),
													'mother' => __( 'Mother', 'foopeople' ),
													'other' => __( 'Other', 'foopeople' ),
												)
											),
											array(
												'id'       => 'manage',
												'type'     => 'repeater-delete',
											),
										)
									)
								)
							), //dependants

						)
					), //personal

					array(
						'id'     => 'portrait',
						'label'  => __( 'Portrait', 'foopeople' ),
						'icon'   => 'dashicons-format-image',
						'fields' => array(
							array(
								'id' => 'portrait',
								'metabox_id' => 'postimagediv',
								'type'   => 'embed-metabox',
							)
						)
					), //portait
				)
			);
		}
	}
}

/**
 * BLOCK: fooplugins/foopeople
 *
 * Registering a basic FooPeople block with Gutenberg.
 */

import { __ } from 'wp.i18n';
import { registerBlockType } from 'wp.blocks';
import { TextControl, CheckboxControl, SelectControl } from 'wp.components';
import { useState } from 'wp.element';
import { withState } from 'wp.compose';


/**
 * Register: a Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'fooplugins/foopeople-listing', {

	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'FooPeople Team Listing' ), // Block title.
	description: __( 'Insert a FooPeople listing into your content' ),
	icon: 'groups', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'foopeople' ),
		__( 'people' ),
		__( 'listing' )
	],
	supports: {
		multiple: true,
		html: false
	},
	attributes: {
		className: {
			type: 'string'
		},
		team: {
			type: 'string',
			default: ''
		},
		showSearch: {
			type: 'boolean',
			default: true
		}
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.

	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Component.
	 */
	edit: ( props ) => {
		const data = JSON.parse(foopeopleListing);
		const teams = [];

		// for ( let index = 0; index < data.length; index++ ) {
		// 	teams.push(
		// 		<p> {data[index].name} / {data[index].slug} </p>
		// 	);
		// }

		const {
			className,
			attributes: { team, showSearch },
			setAttributes
		} = props;

		const [ isChecked, setChecked ] = useState( true );

		const onChangeSearchVisibility = ( value ) => {
			setChecked();
			setAttributes({ showSearch: value });
		};

		const onChangeTeam = ( value ) => {
			// setState();
			setState( { value } );
			setAttributes({ team: value });
		};


		return (
			<div>
				<div class="form-field form-required term-name-wrap">
					<label>
						<div>
							Team Slug or ID
						</div>
						<SelectControl
							label={ __( 'Choose a team' ) }
							onChange={ onChangeTeam  }
							options={ data }
						/>
					</label>
					<p>The Slug or ID of your team you want to show. Leave blank to show everyone.</p>
				</div>

				<CheckboxControl
					label="Show the search box for this team"
					checked={ isChecked }
					onChange={ onChangeSearchVisibility }
				/>
			</div>
		);

	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Frontend HTML.
	 */
	save: ( ) => {
		return null;
	}


});

/**
 * BLOCK: fooplugins/foopeople
 *
 * Registering a basic FooPeople block with Gutenberg.
 */


// import { __ } from '@wordpress/i18n';
// import { registerBlockType } from '@wordpress/blocks';
// import { RichText, MediaUpload } from '@wordpress/block-editor';
// import { Button } from '@wordpress/components';


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
	className: {
		type: 'string'
	},
	attributes: {
		title: {
			type: 'string'
		},
		id: {
			type: 'number',
			default: 0
		},
		team : {
			type: 'string',
			default: ''
		},
		team_id : {
			type: 'number'
		},
		show_search : {
			type: 'boolean',
			default : true
		}
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Component.
	 */
	edit: ( props ) => {
		const {
			className,
			attributes: { title, id, team, team_id, show_search },
			setAttributes,
		} = props;

		const onChangeTitle = ( value ) => {
			setAttributes( { title: value } );
		};

		return (
			<div className={ props.className }>
				<RichText
					tagName="h2"
					placeholder={ __(
						'Write Recipe title…',
						'gutenberg-examples'
					) }
					value={ title }
					onChange={ onChangeTitle }
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
	save: ( props ) => {
		// render in PHP
		return null;
	},


});

import { SelectControl } from 'wp.components';
import { Component } from 'wp.element';
import { __ } from 'wp.i18n';

export default class FooPeopleSingleEdit extends Component {

	constructor() {
		super( ...arguments );

		// Ensure that whenever this methods are called the `this` variable correctly points to this instance of the component.
		this.onChangePerson = this.onChangePerson.bind( this );
	}

	onChangePerson( value ) {
		this.props.setAttributes({ person: value });
	}

	render( ) {

		return (
			<div>
				<div class="form-field form-required term-name-wrap">
					<label>
						<SelectControl
							value={ this.props.attributes.person }
							label={ __( 'Choose a person' ) }
							onChange={ this.onChangePerson  }
							options={ this.props.attributes.allPeople }
						/>
					</label>
				</div>
			</div>
		);

	}
}


FooPeopleSingleEdit.defaultProps = {

};

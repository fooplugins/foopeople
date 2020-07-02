import { CheckboxControl, SelectControl } from 'wp.components';
import { useState, Component } from 'wp.element';
import { __ } from 'wp.i18n';
import { withState } from 'wp.compose';

export default class FooPeopleListingEdit extends Component {

	constructor() {
		super( ...arguments );

		// Ensure that whenever this methods are called the `this` variable correctly points to this instance of the component.
		this.onChangeSearchVisibility = this.onChangeSearchVisibility.bind( this );
		this.onChangeTeam = this.onChangeTeam.bind( this );
	}

	onChangeSearchVisibility( value )  {
		// this.setChecked();
		// this.setState({ showSearch: value });
		// this.setAttributes({ showSearch: value });
	}

	onChangeTeam( value ) {
		this.props.setAttributes({ team: value });
	}

	render( ) {

		return (
			<div class="form-field form-required term-name-wrap">
				<label>
					<SelectControl
						value={ this.props.attributes.team }
						label={ __( 'Choose a team' ) }
						onChange={ this.onChangeTeam  }
						options={ this.props.attributes.allTeams }
					/>
				</label>
			</div>
		);

	}
}


FooPeopleListingEdit.defaultProps = {

};

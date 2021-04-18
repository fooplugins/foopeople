import { CheckboxControl, SelectControl } from 'wp.components';
import { Component } from 'wp.element';
import { __ } from 'wp.i18n';

export default class FooPeopleListingEdit extends Component {

	constructor() {
		super( ...arguments );

		// Ensure that whenever this methods are called the `this` variable correctly points to this instance of the component.
		this.onChangeSearchVisibility = this.onChangeSearchVisibility.bind( this );
		this.onChangeTeam = this.onChangeTeam.bind( this );
	}

	onChangeSearchVisibility( value )  {
		this.props.setAttributes({ showSearch: value });
	}

	onChangeTeam( value ) {
		this.props.setAttributes({ team: value });
	}

	render( ) {

		return (
			<div>
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

				<CheckboxControl
					label="Show the search box for this team"
					checked={ this.props.attributes.showSearch }
					onChange={ this.onChangeSearchVisibility }
				/>

			</div>
		);

	}
}


FooPeopleListingEdit.defaultProps = {

};

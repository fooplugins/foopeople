import { CheckboxControl, SelectControl } from 'wp.components';
import { useState, Component } from 'wp.element';
import { __ } from 'wp.i18n';

export default class FooPeopleListingEdit extends Component {

	constructor() {
		super( ...arguments );

		this.state = {
			team: '',
			showSearch: false
		};

		// Ensure that whenever this methods are called the `this` variable correctly points to this instance of the component.
		this.onChangeSearchVisibility = this.onChangeSearchVisibility.bind( this );
		this.onChangeTeam = this.onChangeTeam.bind( this );
	}

	onChangeSearchVisibility( value )  {
		// this.setChecked();
		// this.setState({ showSearch: value });
		this.setAttributes({ showSearch: value });
	}

	onChangeTeam( value ) {
		// this.setState({ team: value });
		this.setAttributes({ team: value });
	}

	render() {
		const data = JSON.parse(foopeopleListing);

		const {
			attributes: { team, showSearch }
		} = this.props;

		// const {
		// 	team,
		// 	showSearch
		// } = this.state;

		// const [ isChecked, setChecked ] = useState( true );

		return (
			<>
				<div class="form-field form-required term-name-wrap">
					<label>
						<SelectControl
							label={ __( 'Choose a team' ) }
							onChange={ this.onChangeTeam  }
							options={ data }
							value={ this.attributes.team }
						/>
					</label>
				</div>

				<CheckboxControl
					label="Show the search box for this team"
					checked={ isChecked }
					onChange={ this.onChangeSearchVisibility }
					value={ this.attributes.showSearch }
				/>
			</>
		);

	}
}


FooPeopleListingEdit.defaultProps = {

}
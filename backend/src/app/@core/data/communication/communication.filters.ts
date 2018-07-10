export class CommunicationFilters {
	public viewed: number  = -1;
	public replied: number = -1;

	// pagination
	public pageNumber: number = 0;
	public perPage: number    = 10;

	constructor () {}

	/**
	 * This method will define if a specific attribute is set and should be used but the formatRequest() method.
	 *
	 * @param {string} attribute
	 *
	 * @return {boolean}
	 */
	public isSet ( attribute ) {
		if (this[ attribute ] === null || this[ attribute ] === undefined) {
			return false;
		}

		return (this[ attribute ] !== -1);
	}

	/**
	 * This method is used to set filters to specific values.
	 *
	 * @param {string} attr
	 * @param {any}    value
	 */
	public set ( attr, value ) {
		this[ attr ] = value;
	}

	/**
	 * This method will assign the pagination parameter values to the filters attributes.
	 *
	 * @param {{currentPage:number, perPage:number}} pagination
	 */
	public setPagination ( pagination ) {
		this.pageNumber = pagination.currentPage;
		this.perPage    = pagination.perPage;
	}

	/**
	 * This method will get all filters that are set and will create an object usable in services requests.
	 *
	 * @return {object}
	 */
	public formatRequest ( withPagination: boolean = true ): object {
		const params: any = {};

		if (withPagination) {
			params[ "per-page" ] = this.perPage;
			params[ "page" ]     = this.pageNumber;
		}

		if (this.isSet("replied")) {
			params[ "replied" ] = this.replied;
		}

		if (this.isSet("viewed")) {
			params[ "viewed" ] = this.viewed;
		}

		return { params : params };
	}
}
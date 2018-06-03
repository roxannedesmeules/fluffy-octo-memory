export class PostFilters {
	// pagination
	public pageNumber: number = 0;
	public perPage: number    = 10;

	constructor () {}

	public isSet ( attribute ) {
		if (this[ attribute ] === null || this[ attribute ] === undefined) {
			return false;
		}

		return (this[ attribute ] !== -1);
	}

	public reset () {
		this.pageNumber = 0;
		this.perPage    = 10;
	}

	/**
	 *
	 * @param {string} attribute
	 * @param {any}    value
	 */
	public set ( attribute, value ) {
		this[ attribute ] = value;
	}

	public setPagination ( data ) {
		this.pageNumber = data.currentPage;
		this.perPage    = data.perPage;
	}

	public formatRequest (): object {
		const params: any = {};

		params[ "page" ]     = this.pageNumber;
		params[ "per-page" ] = this.perPage;

		return { params : params };
	}
}

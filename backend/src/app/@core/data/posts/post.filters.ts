export class PostFilters {
	// filters
	public status: number = -1;
	public lang: string;

	// sorting
	public orderBy: string;

	// pagination
	public pageNumber: number = 0;
	public perPage: number    = 10;

	constructor () {}

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

		if (this.status !== -1) {
			params.status = this.status;
		}

		if (this.lang) {
			params.lang = this.lang;
		}

		params[ "page" ]     = this.pageNumber;
		params[ "per-page" ] = this.perPage;

		return { params : params };
	}
}

export class PostFilters {
	protected static DEFAULT_CURRENT_PAGE = 0;
	protected static DEFAULT_PER_PAGE     = 10;

	// pagination
	public pageNumber: number = PostFilters.DEFAULT_CURRENT_PAGE;
	public perPage: number    = PostFilters.DEFAULT_PER_PAGE;

	// category
	public category: string = "";

	constructor () {}

	public isSet ( attribute ) {
		if (this[ attribute ] === null || this[ attribute ] === undefined) {
			return false;
		}

		return (this[ attribute ] !== -1 && this[ attribute ] !== "");
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
		this.pageNumber = data.currentPage || PostFilters.DEFAULT_CURRENT_PAGE;
		this.perPage    = data.perPage || PostFilters.DEFAULT_PER_PAGE;
	}

	public formatRequest (): object {
		const params: any = {};

		if (this.isSet("category")) {
			params[ "category" ] = this.category;
		}

		params[ "page" ]     = this.pageNumber;
		params[ "per-page" ] = this.perPage;

		return { params : params };
	}
}

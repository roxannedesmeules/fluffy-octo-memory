export class PostFilters {
	protected static DEFAULT_CURRENT_PAGE = 0;
	protected static DEFAULT_PER_PAGE     = 10;

	// pagination
	public pageNumber: number = PostFilters.DEFAULT_CURRENT_PAGE;
	public perPage: number    = PostFilters.DEFAULT_PER_PAGE;

	// filter by relation
	public category: string = "";
	public tag: string = "";

	constructor () {}

	/**
	 * This method will verify if a specific attribute has a value. This method is mostly used when time to format all
	 * filters for a request.
	 *
	 * @param {string} attribute
	 *
	 * @return {boolean}
	 */
	public isSet ( attribute: string ) {
		if (this[ attribute ] === null || this[ attribute ] === undefined) {
			return false;
		}

		return (this[ attribute ] !== -1 && this[ attribute ] !== "");
	}

	/**
	 * This will reset all filter attributes to their default values.
	 */
	public reset () {
		this.category = "";
		this.tag      = "";

		this.pageNumber = PostFilters.DEFAULT_CURRENT_PAGE;
		this.perPage    = PostFilters.DEFAULT_PER_PAGE;
	}

	/**
	 * Dynamically set an filter attribute with a specific value, this method is used when the update of the filters is
	 * dynamic and the attribute to be changed is a variable.
	 *
	 * @param {string} attribute
	 * @param {any}    value
	 */
	public set ( attribute: string, value: any ) {
		this[ attribute ] = value;
	}

	/**
	 * This method will get the data passed in parameter and set the pagination attributes. In case the data is empty
	 * or one of the attribute isn't set, the default values will be used.
	 *
	 * @see PostFilters.DEFAULT_PER_PAGE     to see the default number of items returned in a request
	 * @see PostFilters.DEFAULT_CURRENT_PAGE to see the default page that should be returned in a request.
	 *
	 * @param {any} data
	 */
	public setPagination ( data ) {
		this.pageNumber = data.currentPage || PostFilters.DEFAULT_CURRENT_PAGE;
		this.perPage    = data.perPage || PostFilters.DEFAULT_PER_PAGE;
	}

	/**
	 * This method will get each attributes that are set and create a object with it, that will be used by a service to
	 * filter list request.
	 *
	 * @return {object}
	 */
	public formatRequest (): object {
		const params: any = {};

		if (this.isSet("category")) {
			params[ "category" ] = this.category;
		}

		if (this.isSet("tag")) {
			params[ "tag" ] = this.tag;
		}

		params[ "page" ]     = this.pageNumber;
		params[ "per-page" ] = this.perPage;

		return { params : params };
	}
}

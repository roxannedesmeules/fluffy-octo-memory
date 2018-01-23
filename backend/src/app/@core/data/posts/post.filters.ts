export class PostFilters {
	// filters
	public status: number = -1;
	public lang: string;

	// sorting
	public orderBy: string;

	// pagination
	public pageNumber: number = 0;
	public pageSize: number   = 20;

	constructor () {}

	/**
	 *
	 * @param {string} attribute
	 * @param {any}    value
	 */
	public set ( attribute, value ) {
		this[ attribute ] = value;
	}

	public formatRequest (): object {
		return {
			params : {
				status : this.status,
				lang   : this.lang,
			},
		};
	}
}
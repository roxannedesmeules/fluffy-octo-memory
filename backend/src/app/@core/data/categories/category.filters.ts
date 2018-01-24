export class CategoryFilters {
	public active: number = -1;
	public lang: string;

	// sorting
	public orderBy: string;

	// pagination
	public pageNumber: number = 0;
	public pageSize: number   = 20;

	constructor () {}

	/**
	 *
	 * @param attr
	 * @param value
	 */
	public set ( attr, value ) {
		this[ attr ] = value;
	}

	public formatRequest (): object {
		const params: any = {};

		if (this.active !== -1) {
			params.active = this.active;
		}

		if (this.lang) {
			params.lang = this.lang;
		}

		return { params : params };
	}
}
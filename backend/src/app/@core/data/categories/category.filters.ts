export class CategoryFilters {
	public active: number = -1;

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
		return {
			params : {
				active : this.active,
			},
		};
	}
}
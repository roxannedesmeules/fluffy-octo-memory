export class CategoryCount {
	public id: number;
	public count: number = 0;

	constructor ( model: any = null ) {
		if (!model) {
			return;
		}

		this.id    = model.id;
		this.count = model.count;
	}
}

export class Item {
	public id: string;
	public title: string;
	public link: string;
	public icon: string;
	public type: string;
	public children: Item[];

	constructor ( model: any = null ) {
		if (!model) {
			return;
		}

		this.id    = model.id || '';
		this.title = model.title || '';
		this.link  = model.link || '';
		this.icon  = model.icon || '';
		this.children = this.mapChildren(model.children || []);
		this.type  = this.getType();
	}

	protected getType () {
		if (this.children.length !== 0) {
			return 'dropdown';
		}

		return 'link';
	}

	/**
	 *
	 * @param {any[]} items
	 * @return {any[]}
	 */
	protected mapChildren ( items: any[] ) {
		items.forEach((item: any, idx: number) => {
			items[ idx ] = new Item(item);
		});

		return items;
	}
}

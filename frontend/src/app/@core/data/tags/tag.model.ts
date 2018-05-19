export class Tag {
	public id: number;
	public name: string;
	public slug: string;

	constructor ( model: any = null ) {
		if ( !model ) return;

		this.id   = model.id;
		this.name = model.name;
		this.slug = model.slug;
	}
}

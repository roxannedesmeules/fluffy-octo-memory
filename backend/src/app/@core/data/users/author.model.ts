export class Author {
	public id: number;
	public fullname: string;

	constructor ( model: any = null ) {
		if (!model) { return; }

		this.id       = model.id;
		this.fullname = model.fullname;
	}
}
export class Author {
	public id: number;
	public fullname: string;
	public firstname: string;
	public lastname: string;
	public picture: string;

	constructor ( model: any = null ) {
		if (!model) {
			return;
		}

		this.id        = model.id;
		this.fullname  = model.fullname;
		this.firstname = model.firstname;
		this.lastname  = model.lastname;
		this.picture   = model.picture;
	}
}
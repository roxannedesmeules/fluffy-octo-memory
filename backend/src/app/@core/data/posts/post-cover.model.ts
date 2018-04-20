export class PostCover {
	public id: number;
	public name: string;
	public path: string;
	public created_on: string;

	constructor ( model: any = null ) {
		if (!model) { return; }

		this.id   = model.id;
		this.name = model.name;
		this.path = model.path;

		this.created_on = model.created_on;
	}
}
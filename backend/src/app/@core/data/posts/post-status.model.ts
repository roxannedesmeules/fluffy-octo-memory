export class PostStatus {
	public id: number;
	public name: string;

	constructor ( model: any ) {
		if (!model) { return; }

		this.id   = model.id;
		this.name = model.name;
	}
}
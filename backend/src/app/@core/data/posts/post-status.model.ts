export class PostStatus {
	public id: number;
	public name: string;

	constructor ( model: any ) {
		if (!model) { return; }

		this.id   = parseInt(model.id, 10);
		this.name = model.name;
	}
}
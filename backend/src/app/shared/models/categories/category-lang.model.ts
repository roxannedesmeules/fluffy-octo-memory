export class CategoryLang {
	public language: string;
	public name: string;
	public slug: string;

	constructor (model: any = null) {
		if (!model) { return; }

		this.language = model.language;
		this.name     = model.name;
		this.slug     = model.slug;
	}
}

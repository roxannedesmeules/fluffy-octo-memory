export class CategoryLang {
	public language: string;
	public lang_id: number;
	public name: string;
	public slug: string;

	constructor (model: any = null) {
		if (!model) { return; }

		this.language = model.language;
		this.lang_id  = model.lang_id;
		this.name     = model.name;
		this.slug     = model.slug;
	}
}

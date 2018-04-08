export class CategoryLang {
	public category_id: number;
	public lang_id: number;
	public language: string;
	public name: string;
	public slug: string;

	constructor ( model: any = null, categoryId?: number ) {
		if (!model) { return; }

		this.category_id = categoryId;
		this.lang_id     = model.lang_id;
		this.language    = model.language;
		this.name        = model.name;
		this.slug        = model.slug;
	}
}

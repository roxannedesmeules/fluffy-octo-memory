export class TagLang {
	public tag_id: number;
	public lang_id: number;

	public language: string;
	public name: string;
	public slug: string;

	constructor ( model: any = null, tagId?: any) {
		if (!model) { return; }

		this.tag_id   = tagId || null;
		this.lang_id  = model.lang_id;
		this.language = model.language;
		this.name     = model.name;
		this.slug     = model.slug;
	}
}

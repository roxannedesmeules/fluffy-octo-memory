export class PostLang {
	public lang_id: number;
	public language: string;
	public title: string;
	public slug: string;
	public content: string;
	protected created_on: string;
	protected updated_on: string;

	//  TODO   remember implementing file upload

	constructor ( model: any = null ) {
		if (!model) { return; }

		this.lang_id    = model.lang_id;
		this.language   = model.language;
		this.title      = model.title;
		this.slug       = model.slug;
		this.content    = model.content;
		this.created_on = model.created_on;
		this.updated_on = model.updated_on;
	}

}
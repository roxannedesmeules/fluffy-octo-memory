import { Lang } from "@core/data/languages";
import { Author } from "@core/data/users/author.model";

export class PostLang {
	public post_id: number;
	public lang_id: number;
	public language: Lang;
	public title: string;
	public slug: string;
	public content: string;

	public author: Author;

	public cover: PostCover;
	public cover_alt: string;

	protected created_on: string;
	protected updated_on: string;

	constructor ( model: any = null, postId?: number ) {
		if (!model) { return; }

		this.post_id  = postId;
		this.lang_id  = model.lang_id;
		this.language = new Lang(model.language);
		this.title    = model.title;
		this.slug     = model.slug;
		this.content  = model.content;

		this.cover     = new PostCover(model.cover);
		this.cover_alt = model.cover_alt;

		this.author = new Author(model.author);

		this.created_on = model.created_on;
		this.updated_on = model.updated_on;
	}

}

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
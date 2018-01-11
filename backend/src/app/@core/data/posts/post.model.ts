import { PostLang } from "@core/data/posts/post-lang.model";

/**
 * @class Post
 */
export class Post {
	public id: number;
	public category_id: number;
	public post_status_id: number;
	public user_id: number;
	public username: string;
	public translations: PostLang[] = [];
	protected created_on: string;
	protected updated_on: string;

	constructor ( model: any ) {
		if (!model) { return; }

		this.id             = model.id;
		this.category_id    = model.category_id;
		this.post_status_id = model.post_status_id;
		this.user_id        = model.user_id;
		this.username       = model.username;

		this.created_on = model.created_on;
		this.updated_on = model.updated_on;

		this.translations = this.mapTranslations(model.translations);
	}

	/**
	 *
	 * @param list
	 * @return {CategoryLang[]}
	 */
	mapTranslations ( list: any[] ): PostLang[] {
		list.forEach(( val, idx ) => {
			list[ idx ] = Post.translationModel(val);
		});

		return list;
	}

	/**
	 *
	 * @param model
	 * @return {CategoryLang}
	 */
	static translationModel ( model: any ): PostLang {
		return new PostLang(model);
	}
}
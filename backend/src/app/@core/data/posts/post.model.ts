import { PostLang } from "@core/data/posts/post-lang.model";
import { Tag } from "@core/data/tags";

/**
 * @class Post
 */
export class Post {
	public static NOT_FEATURED = 0;
	public static FEATURED     = 1;

	public id: number;
	public category_id: number;
	public post_status_id: number;
	public is_featured: number = Post.NOT_FEATURED;

	public translations: PostLang[] = [];
	public tags: Tag[] = [];

	public readonly created_on: string;
	public readonly updated_on: string;

	constructor ( model: any = null ) {
		if (!model) { return; }

		this.id             = parseInt(model.id);
		this.category_id    = parseInt(model.category_id);
		this.post_status_id = parseInt(model.post_status_id);
		this.is_featured    = parseInt(model.is_featured);

		this.translations = this.mapTranslations(model.translations);

		this.created_on = model.created_on;
		this.updated_on = model.updated_on;
	}

	/**
	 *
	 * @param {any[]} list
	 * @return {PostLang[]}
	 */
	mapTranslations ( list: any[] ): PostLang[] {
		list.forEach(( val, idx ) => {
			list[ idx ] = new PostLang(val, this.id);
		});

		return list;
	}

	/**
	 *
	 * @param {any[]} toCompare
	 *
	 * @return {object}
	 */
	compareTags ( toCompare: any[] ) {
		let toAdd    = [];
		let toDelete = [];

		this.tags.forEach((tag) => {
			if (toCompare.indexOf(tag.id) < 0) {
				toDelete.push(tag.id);
			}
		});

		toCompare.forEach((id) => {
			if (this.tags.indexOf(id) < 0) {
				toAdd.push(id);
			}
		});

		return { add : toAdd, delete : toDelete };
	}

	/**
	 *
	 * @param {number | string} lang
	 *
	 * @return {CategoryLang}
	 */
	findTranslation ( lang: number | string ) {
		let result = new PostLang();

		if (!this.translations) {
			return result;
		}

		this.translations.forEach(( val ) => {
			if (typeof lang === "string" && val.language.icu === lang) {
				result = val;
			}

			if (typeof lang === "number" && val.language.id === lang) {
				result = val;
			}
		});

		return result;
	}

	firstTranslation (): PostLang {
		return this.translations[ 0 ];
	}

	/**
	 *
	 * @param model
	 */
	form ( model: any ): any {
		return {
			category_id    : model.category_id,
			post_status_id : model.post_status_id,
			is_featured    : model.is_featured,
			translations   : this.mapFormTranslations(model.translations),
		};
	}

	hasComments () {
		let total = 0;

		this.translations.forEach((val) => { total += val.comments_count; });

		return (total > 0);
	}

	isFeatured () {
		return (this.is_featured === Post.FEATURED);
	}

	isNotFeatured () {
		return (this.is_featured === Post.NOT_FEATURED);
	}

	/**
	 *
	 * @param list
	 * @return {any[]}
	 */
	mapFormTranslations ( list: any ): any[] {
		const result: any[] = [];

		list.forEach(( val ) => {
			let temp: any = new PostLang(val, this.id);
				temp      = temp.form();

			if (temp.title || temp.slug || temp.content || temp.file_alt) {
				result.push(temp);
			}
		});

		return result;
	}
}
import { TagLang } from "./tag-lang.model";

/**
 *
 */
export class Tag {
	public id: number;
	public translations: TagLang[] = [];
	public created_on: string;
	public updated_on: string;

	constructor ( model: any = null ) {
		if (!model) { return; }

		this.id         = model.id;
		this.created_on = model.created_on;
		this.updated_on = model.updated_on;

		this.translations = this.mapTranslations(model.translations);
	}

	/**
	 *
	 * @param list
	 * @return {TagLang[]}
	 */
	mapTranslations ( list: any[] ): TagLang[] {
		list.forEach(( val, idx ) => {
			list[ idx ] = new TagLang(val);
		});

		return list;
	}

	/**
	 *
	 * @param {number | string} lang
	 *
	 * @return {TagLang}
	 */
	findTranslation ( lang: number | string ) {
		let result = new TagLang();

		if (!this.translations) {
			return result;
		}

		this.translations.forEach(( val ) => {
			if (typeof lang === "string" && val.language === lang) {
				result = val;
			}

			if (typeof lang === "number" && val.lang_id === lang) {
				result = val;
			}
		});

		return result;
	}

	firstTranslation (): TagLang {
		return this.translations[ 0 ];
	}

	/**
	 *
	 * @param model
	 */
	form ( model: any ): any {
		return {
			is_active    : (model.is_active) ? 1 : 0,
			translations : this.mapFormTranslations(model.translations),
		};
	}

	/**
	 *
	 * @param list
	 * @return {TagLang[]}
	 */
	mapFormTranslations ( list: any ): TagLang[] {
		const result: TagLang[] = [];

		list.forEach(( val ) => {
			if (val.name || val.slug) {
				result.push(new TagLang(val));
			}
		});

		return result;
	}
}

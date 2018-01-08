import { CategoryLang } from "@core/data/categories/category-lang.model";

/**
 *
 */
export class Category {
	public id: number;
	public is_active: boolean;
	public translations: CategoryLang[];
	public created_on: string;
	public updated_on: string;

	constructor ( model: any = null ) {
		if (!model) { return; }

		this.id         = model.id;
		this.is_active  = model.is_active;
		this.created_on = model.created_on;
		this.updated_on = model.updated_on;

		this.translations = this.mapTranslations(model.translations);
	}

	/**
	 *
	 * @param list
	 * @return {CategoryLang[]}
	 */
	mapTranslations ( list: any[] ): CategoryLang[] {
		list.forEach(( val, idx ) => {
			list[ idx ] = Category.translationModel(val);
		});

		return list;
	}

	/**
	 *
	 * @param model
	 * @return {CategoryLang}
	 */
	static translationModel ( model: any ): CategoryLang {
		return new CategoryLang(model);
	}

	/**
	 *
	 * @param {number | string} lang
	 *
	 * @return {CategoryLang}
	 */
	findTranslation ( lang: number | string ) {
		let result = new CategoryLang();

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

	/**
	 *
	 * @param model
	 */
	form ( model: any ): Category {
		this.is_active = model.is_active;

		this.translations = this.mapFormTranslations(model.translations);

		return this;
	}

	/**
	 *
	 * @param list
	 * @return {CategoryLang[]}
	 */
	mapFormTranslations ( list: any ): CategoryLang[] {
		const result: CategoryLang[] = [];

		list.forEach(( val ) => {
			if (val.name || val.slug) {
				result.push(this.translationModel(val));
			}
		});

		return result;
	}
}

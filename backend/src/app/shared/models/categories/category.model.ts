import { CategoryLang } from "models/categories/category-lang.model";

//  export sub models for easier imports
export { CategoryLang };

/**
 *
 */
export class Category {
	public id: number;
	public is_active: boolean;
	public translations: CategoryLang[];
	public created_on: string;
	public updated_on: string;

	constructor (model: any = null) {
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
	mapTranslations (list: any): CategoryLang[] {
		list.for((val, idx) => {
			list[ idx ] = this.translationModel(val);
		});

		return list;
	}

	/**
	 *
	 * @param model
	 * @return {CategoryLang}
	 */
	translationModel (model: any): CategoryLang {
		return new CategoryLang(model);
	}
}

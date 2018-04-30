import { Lang } from "@core/data/languages";

export class UserProfile {
	public firstname: string;
	public lastname: string;
	public fullname: string;
	public birthday: any;
	public picture: string;

	public translations: UserProfileTranslation[] = [];

	constructor ( model: any = null ) {
		if (!model) {
			return;
		}

		this.firstname    = model.firstname;
		this.lastname     = model.lastname;
		this.fullname     = model.fullname;
		this.birthday     = model.birthday;
		this.picture      = model.picture;
		this.translations = this.mapListToModelList(model.translations);
	}

	/**
	 *
	 * @param {any[]} list
	 * @return {UserProfileTranslation[]}
	 */
	private mapListToModelList ( list: any[] ): UserProfileTranslation[] {
		list.forEach(( item, idx ) => {
			list[ idx ] = new UserProfileTranslation(item);
		});

		return list;
	}

	/**
	 * Transform the birthday date to an object to be use by a datepicker.
	 *
	 * @return {object}
	 */
	public birthdayToDatepicker (): object {
		const date = new Date(this.birthday);

		return { year : date.getFullYear(), month : date.getMonth() + 1, day : date.getDate() + 1 };
	}

	/**
	 *
	 * @return {string}
	 */
	public birthdayToString (): string {
		return this.birthday.year + "-" + this.birthday.month + "-" + this.birthday.day;
	}

	public findTranslation ( langIcu: string ): UserProfileTranslation {
		let translation: UserProfileTranslation = new UserProfileTranslation();

		if (this.translations.length === 0) {
			return translation;
		}

		this.translations.forEach(( item ) => {
			if (item.language.icu === langIcu) {
				translation = item;
			}
		});

		return translation;
	}

	/**
	 *
	 * @return {string}
	 */
	public getInitials (): string {
		const names = this.fullname.split(" ");

		return names.map(n => n.charAt(0)).splice(0, 2).join("").toUpperCase();
	}

	/**
	 *
	 * @return {object}
	 */
	public form (): object {
		return {
			firstname    : this.firstname,
			lastname     : this.lastname,
			birthday     : this.birthdayToString(),
			translations : this.mapFormTranslations(this.translations),
		};
	}

	private mapFormTranslations ( list: any[] ) {
		const result = [];

		list.forEach(( item ) => {
			const temp = new UserProfileTranslation(item);

			result.push(temp.form());
		});

		return result;
	}
}

export class UserProfileTranslation {
	public language: Lang;
	public lang_id: number;
	public job_title: string;
	public biography: string;

	constructor ( model: any = null ) {
		if (!model) {
			return;
		}

		this.language  = model.language;
		this.lang_id   = (this.language) ? this.language.id : model.lang_id;
		this.job_title = model.job_title;
		this.biography = model.biography;
	}

	public form (): object {
		return {
			lang_id   : (this.language) ? this.language.id : this.lang_id,
			job_title : this.job_title,
			biography : this.biography,
		};
	}
}

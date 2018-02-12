export class UserProfile {
	public firstname: string;
	public lastname: string;
	public fullname: string;
	public birthday: string;

	constructor ( model: any = null ) {
		if ( model ) {
			this.firstname = model.firstname;
			this.lastname  = model.lastname;
			this.fullname  = model.fullname;
			this.birthday  = model.birthday;
		}
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

	public  getInitials (): string {
		const names = this.fullname.split(" ");

		return names.map(n => n.charAt(0)).splice(0, 2).join("").toUpperCase();
	}
}

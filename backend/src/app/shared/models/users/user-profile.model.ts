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
}

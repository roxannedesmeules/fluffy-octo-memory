export class UserAuthForm {
	public username: string;
	public password: string;

	constructor (model: any = null) {
		if (model) {
			this.username = model.username;
			this.password = model.password;
		}
	}
}

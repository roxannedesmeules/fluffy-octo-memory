import { UserProfile } from "./user-profile.model";

export { UserProfile };

export class User {
	public id: number;
	public username: string;
	public auth_token: string;
	public last_login: string;
	public is_locked: boolean = false;
	public profile: UserProfile;

	constructor (model: any = null) {
		if (model) {
			this.id         = model.id;
			this.username   = model.username;
			this.auth_token = model.auth_token;
			this.last_login = model.last_login;
			this.profile    = new UserProfile(model.profile);
		}
	}

	public lockSession () {
		this.is_locked = true;
	}

	public unlockSession () {
		this.is_locked = false;
	}
}

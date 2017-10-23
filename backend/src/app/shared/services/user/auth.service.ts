import { Inject, Injectable } from "@angular/core";
import { Http } from "@angular/http";
import { BaseService } from "services/base.service";
import { UserAuthForm } from "models/users/user-auth-form.model";
import { User } from "models/users/user.model";
import { UserService } from "services/user/user.service";

@Injectable()
export class AuthService extends BaseService {
	public redirectUrl: string;
	public modelName = "auth";

	constructor (@Inject(Http) http: Http, private userService: UserService) {
		super(http);

		this.model = (construct: any) => { return new User(construct); };
	}

	public login (body: UserAuthForm): Promise<any> {
		return this.create(body).then(this._parseResponseBody).catch(this._parseErrorBody);
	}

	public logout () {
		return this.http.delete(this._url()).toPromise().then((result: any) => {
			this.userService.removeAppUser();
		});
	}

	/**
	 * Set the "is_locked" value for the authenticated user to true.
	 */
	public lockSession () {
		const user     = this.userService.getAppUser();
		user.is_locked = true;

		this.userService.saveAppUser(user);
	}

	/**
	 * Check if a user is logged in.
	 *
	 * @return {boolean}
	 */
	public isLoggedIn () {
		if (this.userService.getAppUser()) {
			return true;
		} else {
			return false;
		}
	}

	public isLockedOut () {
		return this.userService.getAppUser().is_locked;
	}
}

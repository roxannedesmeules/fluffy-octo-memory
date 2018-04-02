import { Inject, Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { UserService } from "./user.service";
import { BaseService } from "../base.service";
import { User } from "./user.model";
import { UserAuthForm } from "./auth.form";

@Injectable()
export class AuthService extends BaseService {
	public redirectUrl: string;
	public modelName = "auth";

	constructor (@Inject(HttpClient) http: HttpClient, private userService: UserService) {
		super(http);

		//  defines which model to use for mapping
		this.model = (construct: any) => new User(construct);
	}

	/**
	 *
	 * @param {UserAuthForm} body
	 * @return {Promise<any>}
	 */
	public login (body: UserAuthForm): Promise<any> {
		return this.create(body);
	}

	/**
	 *
	 * @return {Promise<any>}
	 */
	public logout (): Promise<any> {
		return this.http.delete(this._url())
			.toPromise()
			.then((result: any) => {
				this.userService.removeAppUser();
			});
	}

	/**
	 * Set the "is_locked" value for the authenticated user to true.
	 */
	public lockSession (): Promise<any> {
		return this.http.delete(this._url())
			.toPromise()
			.then((result: any) => {
				const user = this.userService.getAppUser();
				user.lockSession();

				this.userService.saveAppUser(user);
			});
	}

	/**
	 *
	 * @param {UserAuthForm} body
	 *
	 * @return {Promise<any>}
	 */
	public unlockSession (body: UserAuthForm): Promise<any> {
		return this.create(body).then(this._parseResponseBody).catch(this._parseErrorBody);
	}

	/**
	 * Check if a user is logged in.
	 *
	 * @return {boolean}
	 */
	public isLoggedIn () {
		return (this.userService.getAppUser()) ? true : false;
	}

	/**
	 * Check is user is currently authenticated but in a lock session
	 *
	 * @return {boolean}
	 */
	public isLockedOut () {
		return this.userService.getAppUser().isSessionLock();
	}
}

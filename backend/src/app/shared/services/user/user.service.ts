import { Inject, Injectable } from "@angular/core";
import { Http } from "@angular/http";
import { BaseService } from "services/base.service";
import { User } from "models/users/user.model";

@Injectable()
export class UserService extends BaseService {
	private STORAGE_KEY = "user";

	modelName = "user";

	constructor (@Inject(Http) http: Http) {
		super(http);

		this.model = (construct: any) => { return new User(construct); };
	}

	/**
	 *
	 * @return {any}
	 */
	public getAppUser () {
		return JSON.parse(localStorage.getItem(this.STORAGE_KEY));
	}

	/**
	 *
	 * @param {User} user
	 */
	public saveAppUser (user: User) {
		localStorage.setItem(this.STORAGE_KEY, JSON.stringify(user));
	}
}

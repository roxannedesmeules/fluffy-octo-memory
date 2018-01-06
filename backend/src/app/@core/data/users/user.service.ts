import { Inject, Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { BaseService } from "../base.service";
import { User } from "./user.model";

@Injectable()
export class UserService extends BaseService {
	private STORAGE_KEY = "user";

	constructor (@Inject(HttpClient) http: HttpClient) {
		super(http);

		this.model = (construct: any) => new User(construct);
	}

	/**
	 * Find and return the user object saved in local storage
	 * @return {any}
	 */
	public getAppUser () {
		return JSON.parse(localStorage.getItem(this.STORAGE_KEY));
	}

	/**
	 * Delete the user object saved in local storage
	 */
	public removeAppUser () {
		localStorage.removeItem(this.STORAGE_KEY);
	}

	/**
	 * Update the user object saved in local storage
	 * @param {User} user
	 */
	public saveAppUser (user: User) {
		localStorage.setItem(this.STORAGE_KEY, JSON.stringify(user));
	}
}

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

	public logout () {}

	public lockSession () {}

	public isLoggedIn () {
		if (this.userService.getAppUser()) {
			return true;
		} else {
			return false;
		}
	}

	public isLockedOut () {}
}

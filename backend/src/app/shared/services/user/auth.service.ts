import { Inject, Injectable } from "@angular/core";
import { Http } from "@angular/http";
import { BaseService } from "services/base.service";
import { UserAuthForm } from "models/users/user-auth-form.model";
import { User } from "models/users/user.model";

@Injectable()
export class AuthService extends BaseService {
	public modelName = "auth";

	constructor (@Inject(Http) http: Http) {
		super(http);

		this.model = (construct: any) => { return new User(construct); };
	}

	login (body: UserAuthForm): Promise<any> {
		return this.create(body);
	}

	logout () {}

	lockSession () {}
}

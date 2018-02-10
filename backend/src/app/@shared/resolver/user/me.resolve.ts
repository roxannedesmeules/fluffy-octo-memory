import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve, Router } from "@angular/router";

import { User } from "@core/data/users/user.model";
import { UserService } from "@core/data/users/user.service";

@Injectable()
export class MeResolve implements Resolve<User> {

	constructor ( private _router: Router, private service: UserService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service.mapModel(this.service.getAppUser());
	}
}

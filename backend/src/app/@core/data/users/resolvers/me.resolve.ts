import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve, Router } from "@angular/router";

import { User } from "../user.model";
import { UserService } from "../user.service";

@Injectable()
export class MeResolve implements Resolve<User> {

	constructor ( private _router: Router, private service: UserService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service.mapModel(this.service.getAppUser());
	}
}

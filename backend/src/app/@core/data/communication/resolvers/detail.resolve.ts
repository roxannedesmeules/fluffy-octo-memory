import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve, Router } from "@angular/router";

import { Communication } from "../communication.model";
import { CommunicationService } from "../communication.service";

@Injectable()
export class DetailResolve implements Resolve<Communication> {

	constructor ( private _router: Router, private service: CommunicationService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service
				.findById(route.paramMap.get("id")).toPromise()
				.then(( result: Communication ) => result);
	}
}

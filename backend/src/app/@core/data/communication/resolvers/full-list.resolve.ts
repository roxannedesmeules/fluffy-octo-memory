import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { CommunicationService } from "../communication.service";
import { Communication } from "../communication.model";

@Injectable()
export class FullListResolve implements Resolve<Communication[]> {

	constructor ( private service: CommunicationService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		this.service.filters.setPagination({ currentPage: 0, perPage: -1 });

		return this.service
				.findAll().toPromise()
				.then(( result: Communication[] ) => result);
	}
}

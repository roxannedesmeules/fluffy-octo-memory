import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { PostStatus } from "../post-status.model";
import { PostStatusService } from "../post-status.service";

@Injectable()
export class StatusResolve implements Resolve<PostStatus[]> {

	constructor ( private service: PostStatusService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service
				.findAll().toPromise()
				.then(( result: PostStatus[] ) => result);
	}
}

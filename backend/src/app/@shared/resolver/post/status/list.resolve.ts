import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { PostStatus } from "@core/data/posts/post-status.model";
import { PostStatusService } from "@core/data/posts/post-status.service";

@Injectable()
export class ListResolve implements Resolve<PostStatus[]> {

	constructor ( private service: PostStatusService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service
				.findAll()
				.then(( result: any ) => {
					return this.service.mapListToModelList(result);
				});
	}
}

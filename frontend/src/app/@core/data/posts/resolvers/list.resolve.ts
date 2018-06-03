import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { Post } from "../post.model";
import { PostService } from "../post.service";

@Injectable()
export class ListResolve implements Resolve<Post[]> {

	constructor ( private service: PostService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		this.service.filters.reset();

		return this.service
				.findAll().toPromise()
				.then(( result: Post[] ) => result);
	}
}

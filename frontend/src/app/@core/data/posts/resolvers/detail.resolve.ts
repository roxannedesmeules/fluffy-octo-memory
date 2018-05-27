import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve, Router } from "@angular/router";

import { Post } from "../post.model";
import { PostService } from "../post.service";

@Injectable()
export class DetailResolve implements Resolve<Post> {

	constructor ( private _router: Router, private service: PostService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service
				.findById(route.paramMap.get("post")).toPromise()
				.then(( result: Post ) => result);
	}
}

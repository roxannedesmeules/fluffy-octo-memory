import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve, Router } from "@angular/router";

import { Post } from "@core/data/posts/post.model";
import { PostService } from "@core/data/posts/post.service";

@Injectable()
export class DetailResolve implements Resolve<Post> {

	constructor ( private _router: Router, private service: PostService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service
				.findById(route.paramMap.get("id"))
				.then(( result: any ) => {
					return this.service.mapModel(result);
				});
	}
}

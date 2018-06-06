import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { Post } from "@core/data/posts";
import { CategoryPostService } from "../category-post.service";

@Injectable()
export class PostListResolve implements Resolve<Post[]> {

	constructor ( private service: CategoryPostService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		this.service.filters.reset();

		return this.service
				.getAll(route.paramMap.get("category")).toPromise()
				.then(( result: Post[] ) => result);
	}
}

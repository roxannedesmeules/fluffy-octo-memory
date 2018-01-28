import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { Post } from "@core/data/posts/post.model";
import { PostService } from "@core/data/posts/post.service";

@Injectable()
export class ListResolve implements Resolve<Post[]> {

	constructor ( private service: PostService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service
				.findAll()
				.then(( result: any ) => result);
	}
}

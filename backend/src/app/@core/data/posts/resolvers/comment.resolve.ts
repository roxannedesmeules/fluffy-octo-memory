import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { PostComment } from "../post-comment.model";
import { PostCommentService } from "../post-comment.service";

@Injectable()
export class CommentResolve implements Resolve<PostComment[]> {

	constructor ( private service: PostCommentService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service
				.findAllForPost(route.paramMap.get("id")).toPromise()
				.then(( result: PostComment[] ) => result);
	}
}

import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { Observable } from "rxjs/Observable";
import { catchError, map } from "rxjs/operators";

import { BaseService } from "@core/data/base.service";
import { PostComment } from "@core/data/posts/post-comment.model";

@Injectable()
export class PostCommentService extends BaseService {
	baseUrl   = "/posts";
	modelName = "comments";

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new PostComment(construct);
	}

	findAllForPost ( postId: number | string ): Observable<any> {
		return this.http.get(this._commentsUrl(postId), { observe : "response", })
				   .pipe(
						   map(( res: any ) => this.mapListToModelList(res.body)),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   )
	}

	updateForPost ( postId: number | string, commentId: number | string, body: any ): Observable<any> {
		return this.http.put(this._commentsUrl(postId, commentId), body, { observe : "response" })
				   .pipe(
						   map(( res: any ) => this.mapListToModelList(res.body)),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   )
	}

	protected _commentsUrl (postId: number | string, commentId?: number | string): string {
		return `${this.baseUrl}/${postId}/${this.modelName}` + ((commentId) ? `/${commentId}`: "");
	}

	mapListToModelList ( body ) {
		for (let lang in body) {
			if (!body.hasOwnProperty(lang)) {
				continue;
			}

			body[ lang ] = super.mapListToModelList(body[ lang ]);
		}

		return body;
	}
}

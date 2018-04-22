import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { Observable } from "rxjs/Observable";

import { BaseService } from "@core/data/base.service";
import { catchError } from "rxjs/operators";

@Injectable()
export class PostTagService extends BaseService {
	modelName = "posts-tags";

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);
	}

	link ( body: any ): Observable<any> {
		return this.http.post(this._url(), body)
				   .pipe(
				   		catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}

	linkSeveral ( postId, tagIds: number[] ): Observable<any> {
		const requests = [];

		tagIds.forEach(( id ) => {
			requests.push(this.link({ post_id : postId, tag_id : id }));
		});

		return Observable.forkJoin(requests);
	}

	unlink ( body: any ): Observable<any> {
		return this.http.delete(this._url(), body)
				   .pipe(
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}

	unlinkSeveral ( postId, tagIds: number[] ): Observable<any> {
		const requests = [];

		tagIds.forEach(( id ) => {
			requests.push(this.unlink({ post_id : postId, tag_id : id }));
		});

		return Observable.forkJoin(requests);
	}
}

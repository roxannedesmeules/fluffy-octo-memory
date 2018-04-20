import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
import { Post } from "@core/data/posts/post.model";
import { Observable } from "rxjs/Observable";
import { catchError, map } from "rxjs/operators";
import 'rxjs/add/observable/forkJoin'

@Injectable()
export class PostCoverService extends BaseService {
	baseUrl   = "/posts";
	modelName = "cover";

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new Post(construct);
	}

	/**
	 *
	 * @param {number}   postId
	 * @param {number}   langId
	 * @param {FormData} body
	 *
	 * @return {Observable<Post>}
	 */
	upload ( postId: number, langId: number, body: FormData ): Observable<Post> {
		return this.http.post(this._translationUrl(postId, langId), body)
				   .pipe(
						   map(( res: any ) => this.mapModel(res)),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}

	uploadSeveral ( postId: number, body: any[] ) {
		const requests = [];

		body.forEach((val) => {
			requests.push(this.upload(postId, val.lang_id, val.file));
		});

		return Observable.forkJoin(requests);
	}

	/**
	 *
	 * @param {number} postId
	 * @param {number} langId
	 *
	 * @return {Observable<Post>}
	 */
	delete (postId: number, langId = -1): Observable<Post> {
		return this.http.delete(this._translationUrl(postId, langId))
				   .pipe(
						   map(( res: any ) => this.mapModel(res)),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}

	/**
	 *
	 * @param {number} postId
	 * @param {number} langId
	 *
	 * @return {string}
	 * @private
	 */
	private _translationUrl (postId: number, langId: number): string {
		let url  = this.baseUrl;
			url += `/${postId}`;
			url += `/${langId}`;
			url += `/${this.modelName}`;

		return url;
	}
}

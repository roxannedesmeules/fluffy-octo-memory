import { Inject, Injectable } from "@angular/core";
import { HttpClient, HttpHeaders, HttpResponse } from "@angular/common/http";

import { BaseService } from "@core/data/base.service";
import { Category } from "@core/data/categories/category.model";
import { PostFilters } from "@core/data/posts/post.filters";
import { Post } from "@core/data/posts/post.model";
import { Observable } from "rxjs/Observable";
import { catchError, map } from "rxjs/operators";

@Injectable()
export class PostService extends BaseService {
	public modelName = "posts";

	public responseHeaders: HttpHeaders;

	public filters = new PostFilters();
	public options = {
		observe : "response",
	};

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new Post(construct);
	}

	public findAll () {
		const options = Object.assign({}, this.options, this.filters.formatRequest());

		return this.http.get(this._url(), options)
				   .pipe(
						   map(( res: HttpResponse<Category[]> ) => {
							   this.responseHeaders = res.headers;

							   return this.mapListToModelList(res.body);
						   }),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}
}

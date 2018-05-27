import { HttpClient, HttpHeaders, HttpResponse } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
import { Post } from "@core/data/posts/post.model";
import { Observable } from "rxjs/Observable";
import { catchError, map } from "rxjs/operators";
import { Category } from "../../../../../../backend/src/app/@core/data/categories/category.model";

@Injectable()
export class PostService extends BaseService {
	public modelName = "posts";

	public responseHeaders: HttpHeaders;

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new Post(construct);
	}

	public findAll () {
		return this.http.get(this._url(), { observe : "response", })
				   .pipe(
						   map(( res: HttpResponse<Category[]> ) => {
							   this.responseHeaders = res.headers;

							   return this.mapListToModelList(res.body);
						   }),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}
}

import { HttpClient, HttpHeaders, HttpResponse } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
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
		return this.http.get(this._url(), this._getOptions())
				   .pipe(
						   map(( res: HttpResponse<Post[]> ) => {
							   this.responseHeaders = res.headers;

							   return this.mapListToModelList(res.body);
						   }),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}

	public latests () {
		this.filters.reset();
		this.filters.set("perPage", 3);

		return this.findAll();
	}

	public featured () {
		this.filters.reset();
		this.filters.set("featured", Post.FEATURED);
		this.filters.set("perPage", 3);

		return this.findAll();
	}

	protected _getOptions () {
		return Object.assign({}, this.options, this.filters.formatRequest());
	}
}

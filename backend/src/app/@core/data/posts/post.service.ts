import { Inject, Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { BaseService } from "@core/data/base.service";
import { PostFilters } from "@core/data/posts/post.filters";
import { Post } from "@core/data/posts/post.model";

@Injectable()
export class PostService extends BaseService {
	public modelName = "posts";

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
				.toPromise()
				.then(this._parseResponseBody)
				.catch(this._parseErrorBody);
	}
}

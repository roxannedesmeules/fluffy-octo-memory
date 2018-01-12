import { Inject, Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { BaseService } from "@core/data/base.service";
import { PostStatus } from "@core/data/posts/post-status.model";

@Injectable()
export class PostStatusService extends BaseService {
	public baseUrl   = "/posts";
	public modelName = "statuses";

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new PostStatus(construct);
	}

}

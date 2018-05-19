import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
import { Tag } from "@core/data/tags/tag.model";

@Injectable()
export class TagService extends BaseService {
	public modelName = "tags";

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new Tag(construct);
	}

}

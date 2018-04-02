import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";

import { BaseService } from "@core/data/base.service";
import { Lang } from "@core/data/languages/lang.model";

@Injectable()
export class LangService extends BaseService {
	modelName = "languages";

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => { return new Lang(construct); };
	}

}

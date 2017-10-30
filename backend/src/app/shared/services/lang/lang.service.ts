import { Inject, Injectable } from "@angular/core";
import { Http } from "@angular/http";
import { Lang } from "models/lang/lang.model";
import { BaseService } from "services/base.service";

@Injectable()
export class LangService extends BaseService {
	modelName = "languages";

	constructor (@Inject(Http) http: Http) {
		super(http);

		this.model = (construct: any) => { return new Lang(construct); };
	}

}

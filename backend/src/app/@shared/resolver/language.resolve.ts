import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { Lang } from "@core/data/languages/lang.model";
import { LangService } from "@core/data/languages/lang.service";

@Injectable()
export class LanguageResolve implements Resolve<Lang[]> {

	constructor ( private service: LangService ) {}

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service.findAll().then(( result: any ) => this.service.mapListToModelList(result));
	}

}

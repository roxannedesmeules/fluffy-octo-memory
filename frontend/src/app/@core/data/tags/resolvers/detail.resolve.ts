import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve, Router } from "@angular/router";

import { Tag } from "@core/data/tags/tag.model";
import { TagService } from "@core/data/tags/tag.service";

@Injectable()
export class DetailResolve implements Resolve<Tag> {

	constructor ( private _router: Router, private service: TagService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		if (route.queryParamMap.get("tag")) {
			return this.service
					   .findById(route.queryParamMap.get("tag")).toPromise()
					   .then(( result: Tag ) => result);

		} else {
			return null;
		}
	}
}

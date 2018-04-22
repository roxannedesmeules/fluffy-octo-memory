import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { Tag } from "@core/data/tags/tag.model";
import { TagService } from "@core/data/tags/tag.service";

@Injectable()
export class PartialListResolve implements Resolve<Tag[]> {

	constructor ( private service: TagService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		this.service.filters.resetPagination();

		return this.service
				.findAll().toPromise()
				.then(( result: Tag[] ) => result);
	}
}

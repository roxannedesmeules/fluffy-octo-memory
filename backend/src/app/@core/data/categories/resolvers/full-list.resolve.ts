import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { Category } from "@core/data/categories/category.model";
import { CategoryService } from "@core/data/categories/category.service";

@Injectable()
export class FullListResolve implements Resolve<Category[]> {

	constructor ( private service: CategoryService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		this.service.filters.setPagination({ currentPage : 0, perPage : -1 });

		return this.service
				.findAll().toPromise()
				.then(( result: Category[] ) => result);
	}
}

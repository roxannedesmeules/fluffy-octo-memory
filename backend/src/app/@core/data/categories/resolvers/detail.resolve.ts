import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve, Router } from "@angular/router";

import { Category } from "@core/data/categories/category.model";
import { CategoryService } from "@core/data/categories/category.service";

@Injectable()
export class DetailResolve implements Resolve<Category> {

	constructor ( private _router: Router, private service: CategoryService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service
				.findById(route.paramMap.get("id")).toPromise()
				.then(( result: Category ) => result);
	}
}

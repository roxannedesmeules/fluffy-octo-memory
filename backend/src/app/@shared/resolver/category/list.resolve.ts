import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { Category } from "@core/data/categories/category.model";
import { CategoryService } from "@core/data/categories/category.service";

@Injectable()
export class ListResolve implements Resolve<Category[]> {

	constructor ( private service: CategoryService ) { }

	resolve ( route: ActivatedRouteSnapshot ) {
		return this.service
				.findAll()
				.then(( result: any ) => {
					console.log(result);
					return result;
				});
	}
}

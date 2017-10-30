import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve, Router } from "@angular/router";
import { Category } from "models/categories/category.model";
import { CategoriesService } from "services/categories/categories.service";

@Injectable()
export class DetailsResolve implements Resolve<Category> {

	constructor (private _router: Router, private service: CategoriesService) { }

	resolve (route: ActivatedRouteSnapshot) {
		return this.service
					.findById(route.paramMap.get("id"))
					.then((result: any) => {
						return this.service.mapModel(result);
					});
	}
}

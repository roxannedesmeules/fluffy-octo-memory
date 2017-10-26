import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";
import { Category } from "models/categories/category.model";
import { CategoriesService } from "services/categories/categories.service";

@Injectable()
export class ListResolve implements Resolve<Category[]> {

	constructor (private service: CategoriesService) { }

	resolve (route: ActivatedRouteSnapshot) {
		return this.service
					.findAll()
					.then((result: any) => {
						return this.service.mapListToModelList(result);
					});
	}
}

import { Inject, Injectable } from "@angular/core";
import { BaseService } from "services/base.service";
import { Http } from "@angular/http";
import { Category } from "models/categories/category.model";

@Injectable()
export class CategoriesService extends BaseService {
	modelName = "categories";

	constructor (@Inject(Http) http: Http) {
		super(http);

		this.model = (construct: any) => { return new Category(construct); };
	}
}

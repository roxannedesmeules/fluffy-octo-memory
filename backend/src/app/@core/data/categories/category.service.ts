import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";

import { BaseService } from "@core/data/base.service";
import { CategoryFilters } from "@core/data/categories/category.filters";

import { Category } from "@core/data/categories/category.model";

@Injectable()
export class CategoryService extends BaseService {
	public modelName = "categories";

	public filters = new CategoryFilters();

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new Category(construct);
	}

	public findAll () {
		return this.http.get(this._url(), this.filters.formatRequest())
				.toPromise()
				.then(this._parseResponseBody)
				.catch(this._parseErrorBody);
	}
}

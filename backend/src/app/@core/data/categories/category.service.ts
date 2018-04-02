import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";

import { BaseService } from "@core/data/base.service";
import { CategoryFilters } from "@core/data/categories/category.filters";

import { Category } from "@core/data/categories/category.model";

@Injectable()
export class CategoryService extends BaseService {
	public modelName = "categories";

	public filters = new CategoryFilters();
	public options = {
		observe : "response",
	};

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new Category(construct);
	}

	public findAll () {
		const options = Object.assign({ observe : 'response' }, this.options, this.filters.formatRequest());

		return this.http.get(this._url(), options)
				   .map(( res: any ) => {
					   if (BaseService.SUCCESS_CODES.indexOf(res.status) >= 0) {
						   return this.mapListToModelList(res);
					   } else {
						   return this.mapError(res);
					   }
				   });
	}
}

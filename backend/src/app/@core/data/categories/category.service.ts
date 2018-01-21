import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";

import { BaseService } from "@core/data/base.service";

import { Category } from "@core/data/categories/category.model";

@Injectable()
export class CategoryService extends BaseService {
	public modelName = "categories";

	public filters = {
		active : -1,
	};

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new Category(construct);
	}

	public findAll () {
		return this.http.get(this._url(), this._filters())
				.toPromise()
				.then(this._parseResponseBody)
				.catch(this._parseErrorBody);
	}

	/**
	 *
	 * @return {object}
	 * @private
	 */
	private _filters (): object {
		return {
			params : {
				active : this.filters.active.toString(),
			},
		};
	}
}

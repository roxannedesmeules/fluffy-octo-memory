import { HttpClient, HttpHeaders, HttpResponse } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";

import { BaseService } from "@core/data/base.service";
import { CategoryFilters } from "@core/data/categories/category.filters";

import { Category } from "@core/data/categories/category.model";
import { Observable } from "rxjs/Observable";
import { catchError, map } from "rxjs/operators";

@Injectable()
export class CategoryService extends BaseService {
	public modelName = "categories";

	public responseHeaders: HttpHeaders;

	public filters = new CategoryFilters();
	public options = {
		observe : "response",
	};

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new Category(construct);
	}

	public findAll () {
		const options = Object.assign({}, this.options, this.filters.formatRequest());

		return this.http.get(this._url(), options)
				   .pipe(
						   map(( res: HttpResponse<Category[]> ) => {
						   		this.responseHeaders = res.headers;

						   		return this.mapListToModelList(res.body);
						   }),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}
}

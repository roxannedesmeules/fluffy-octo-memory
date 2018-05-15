import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
import { Observable } from "rxjs/Observable";
import { catchError, map } from "rxjs/operators";
import { CategoryCount } from "./category-count.model";

@Injectable()
export class CategoryPostService extends BaseService {
	public baseUrl   = "categories";
	public modelName = "posts";

	constructor (@Inject(HttpClient) http: HttpClient) {
		super(http);

		this.model = (construct: any) => new CategoryCount(construct);
	}

	getCount () {
		return this.http.get(this._url("count"))
				   .pipe(
						   map(( res: any ) => this.mapListToModelList(res)),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}
}

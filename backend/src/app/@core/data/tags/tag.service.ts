import { HttpClient, HttpHeaders, HttpResponse } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
import { TagFilters } from "@core/data/tags/tag.filters";
import { Tag } from "@core/data/tags/tag.model";

import { Observable } from "rxjs/Observable";
import { catchError, map } from "rxjs/operators";

@Injectable()
export class TagService extends BaseService {
	public modelName = "tags";

	public responseHeaders: HttpHeaders;

	public filters = new TagFilters();
	public options = {
		observe : "response",
	};

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new Tag(construct);
	}

	/**
	 *
	 * @return {Observable<Tag[]>}
	 */
	public findAll (): Observable<Tag[]> {
		const options = Object.assign({}, this.options, this.filters.formatRequest());

		return this.http.get(this._url(), options)
				   .pipe(
						   map(( res: HttpResponse<Tag[]> ) => {
							   this.responseHeaders = res.headers;

							   return this.mapListToModelList(res.body);
						   }),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}
}

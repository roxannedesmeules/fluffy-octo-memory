import { HttpClient, HttpHeaders, HttpResponse } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { Observable } from "rxjs/Observable";
import { catchError, map } from "rxjs/operators";

import { BaseService } from "@core/data/base.service";
import { Communication } from "./communication.model";
import { CommunicationFilters } from "./communication.filters";

@Injectable()
export class CommunicationService extends BaseService {
	modelName = "communications";

	public responseHeaders: HttpHeaders;

	public filters = new CommunicationFilters();
	public options = {
		observe : "response",
	};

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = ( construct: any ) => new Communication(construct);
	}

	public findAll () {
		const options = Object.assign({}, this.options, this.filters.formatRequest());

		return this.http.get(this._url(), options)
				   .pipe(
						   map(( res: HttpResponse<Communication[]> ) => {
							   this.responseHeaders = res.headers;

							   return this.mapListToModelList(res.body);
						   }),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}

	public count () {
		const options = Object.assign({}, this.filters.formatRequest(false));

		return this.http.get(this._url("count"), options)
				   .pipe(
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}
}

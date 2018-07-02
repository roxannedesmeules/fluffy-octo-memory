import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
import { Observable } from "rxjs/Observable";
import { catchError } from "rxjs/operators";

@Injectable()
export class CommunicationService extends BaseService {
	modelName = "communications";

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		super(http);

		this.model = null;
	}

	create ( body: any ): Observable<any> {
		return this.http
				   .post(this._url(), body, { observe : "response" })
				   .pipe( catchError(( err: any ) => Observable.throw(this.mapError(err))) );
	}
}

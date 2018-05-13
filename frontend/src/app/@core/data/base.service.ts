import { Inject, Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable } from "rxjs/Observable";
import { catchError, map } from "rxjs/operators";
import 'rxjs/add/observable/throw';

import { ErrorResponse } from "./error-response.model";

@Injectable()
export abstract class BaseService {
	public http: HttpClient;
	public model: any;
	public modelName: string;
	public baseUrl = "";

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		this.http = http;
	}

	public findAll (): Observable<any> {
		return this.http.get(this._url(), { observe: 'response' })
				   .pipe(
						   map(( res: any ) => this.mapListToModelList(res.body)),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}

	public findOne (): Observable<any> {
		return this.http.get(this._url())
				   .pipe(
						   map(( res: any ) => this.mapModel(res)),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}

	public findById ( id: any ): Observable<any> {
		return this.http.get(this._url(id))
				   .pipe(
						   map(( res: any ) => this.mapModel(res)),
						   catchError(( err: any ) => Observable.throw(this.mapError(err))),
				   );
	}

	/**
	 *
	 * @param {number} id
	 * @return {string}
	 * @private
	 */
	protected _url ( id?: number | string ): string {
		return ((this.baseUrl) ? "/" + this.baseUrl : "") + "/" + this.modelName + ((id) ? "/" + id : "");
	}

	mapError ( err ) {
		return new ErrorResponse(err.error);
	}

	mapListToModelList ( list: any ) {
		list.forEach(( item, index ) => {
			list[ index ] = this.mapModel(item);
		});

		return list;
	}

	mapModel ( model: any ) {
		return this.model(model);
	}
}

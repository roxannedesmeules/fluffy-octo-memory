import { Inject, Injectable } from "@angular/core";
import { HttpClient, HttpErrorResponse } from "@angular/common/http";
import { Observable } from "rxjs/Observable";

import { ErrorResponse } from "./error-response.model";

@Injectable()
export abstract class BaseService {
	public static SUCCESS_CODES = [ 200, 201, 204 ];

	public http: HttpClient;
	public model: any;
	public modelName: string;
	public baseUrl = "";

	constructor ( @Inject(HttpClient) http: HttpClient ) {
		this.http = http;
	}

	public findAll (): Observable<any> {
		return this.http.get(this._url())
				   .map(( res: any ) => {
					   if (BaseService.SUCCESS_CODES.indexOf(res.status) >= 0) {
						   return this.mapListToModelList(res);
					   } else {
						   return this.mapError(res);
					   }
				   });
	}

	public findOne (): Observable<any> {
		return this.http.get(this._url())
				   .map(( res: any ) => {
					   if (BaseService.SUCCESS_CODES.indexOf(res.status) >= 0) {
						   return this.mapModel(res);
					   } else {
						   return this.mapError(res);
					   }
				   });
	}

	public findById ( id: any ): Observable<any> {
		return this.http.get(this._url(id))
				   .map(( res: any ) => {
					   if (BaseService.SUCCESS_CODES.indexOf(res.status) >= 0) {
						   return this.mapModel(res);
					   } else {
						   return this.mapError(res);
					   }
				   });
	}

	public create ( body: any ): Observable<any> {
		return this.http.post(this._url(), body)
				   .map(( res: any ) => {
					   if (BaseService.SUCCESS_CODES.indexOf(res.status) >= 0) {
						   return this.mapModel(res);
					   } else {
						   return this.mapError(res);
					   }
				   });
	}

	public update ( id: number, body: any ): Observable<any> {
		return this.http.put(this._url(id), body)
				   .map(( res: any ) => {
					   if (BaseService.SUCCESS_CODES.indexOf(res.status) >= 0) {
						   return this.mapModel(res);
					   } else {
						   return this.mapError(res);
					   }
				   });
	}

	public delete ( id: number ): Observable<any> {
		return this.http.delete(this._url(id))
				   .map(( res: any ) => {
					   if (BaseService.SUCCESS_CODES.indexOf(res.status) >= 0) {
						   return this.mapModel(res);
					   } else {
						   return this.mapError(res);
					   }
				   });
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

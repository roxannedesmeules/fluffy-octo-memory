import { Inject, Injectable } from "@angular/core";
import { HttpClient, HttpErrorResponse } from "@angular/common/http";

import { ErrorResponse } from "./error-response.model";

@Injectable()
export abstract class BaseService {
	http: HttpClient;
	model: any;
	modelName: string;
	baseUrl = "";

	constructor (@Inject(HttpClient) http: HttpClient) {
		this.http = http;
	}

	public findAll () {
		return this.http.get(this._url()).toPromise()
			.then(this._parseResponseBody)
			.catch(this._parseErrorBody);
	}

	public findOne () {
		return this.http.get(this._url()).toPromise()
			.then(this._parseResponseBody)
			.catch(this._parseErrorBody);
	}

	public findById (id: any) {
		return this.http.get(this._url(id)).toPromise()
			.then(this._parseResponseBody)
			.catch(this._parseErrorBody);
	}

	public create (body: any) {
		return this.http.post(this._url(), body).toPromise()
			.then(this._parseResponseBody)
			.catch(this._parseErrorBody);
	}

	public update (id: number, body: any) {
		return this.http.put(this._url(id), body).toPromise()
			.then(this._parseResponseBody)
			.catch(this._parseErrorBody);
	}

	public delete (id: number) {
		return this.http.delete(this._url(id)).toPromise()
			.then(this._parseResponseBody)
			.catch(this._parseErrorBody);
	}

	/**
	 *
	 * @param {number} id
	 * @return {string}
	 * @private
	 */
	protected _url (id?: number | string): string {
		return this.baseUrl + "/" + this.modelName + ((id) ? "/" + id : "");
	}

	protected _parseResponseBody (response: any) {
		return new Promise(( resolve, reject ) => { resolve(response); });
	}

	protected _parseErrorBody ( error: HttpErrorResponse ) {
		return new Promise(( resolve, reject ) => { reject(new ErrorResponse(error.error)); });
	}

	mapListToModelList (list: any) {
		list.forEach((item, index) => {
			list[ index ] = this.mapModel(item);
		});

		return list;
	}

	mapModel (model: any) { return this.model(model); }
}

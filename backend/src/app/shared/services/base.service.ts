import { Inject, Injectable } from "@angular/core";
import { Http } from "@angular/http";
import { ErrorResponse } from "models/error-response.model";

@Injectable()
export abstract class BaseService {
	http: Http;
	model: any;
	modelName: string;
	baseUrl = "";

	constructor (@Inject(Http) http: Http) {
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

	public findById (id: number) {
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
		return new Promise((resolve, reject) => { resolve(JSON.parse(response._body)); });
	}

	protected _parseErrorBody (error: any) {
		return new Promise((resolve, reject) => { reject(new ErrorResponse(JSON.parse(error._body))); });
	}

	mapListToModelList (list: any) {
		list.forEach((item, index) => {
			list[ index ] = this.mapModel(item);
		});

		return list;
	}

	mapModel (model: any) { return this.model(model); }
}

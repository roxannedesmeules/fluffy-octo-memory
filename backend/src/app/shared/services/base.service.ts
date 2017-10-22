import { Inject, Injectable } from "@angular/core";
import { Http } from "@angular/http";

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
		return this.http.get(this._url()).toPromise();
	}

	public findOne () {
		return this.http.get(this._url()).toPromise();
	}

	public findById (id: number) {
		return this.http.get(this._url(id)).toPromise();
	}

	public create (body: any) {
		return this.http.post(this._url(), body).toPromise();
	}

	public update (id: number, body: any) {
		return this.http.put(this._url(id), body).toPromise();
	}

	public delete (id: number) {
		return this.http.delete(this._url(id)).toPromise();
	}

	/**
	 *
	 * @param {number} id
	 * @return {string}
	 * @private
	 */
	protected _url (id?: number): string {
		return this.baseUrl + "/" + this.modelName + ((id) ? "/" + id : "");
	}

	mapListToModelList (list: any) {
		list.forEach((item, index) => {
			list[ index ] = this.mapModel(item);
		});

		return list;
	}

	mapModel (model: any) { return this.model(model); }
}

import { HttpClient, HttpHandler, HttpHeaders, HttpXhrBackend } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { environment } from "../../../environments/environment";
import { Observable } from "rxjs/Observable";
import "rxjs/add/operator/toPromise";

@Injectable()
export class HttpEx extends HttpClient {

	constructor (handler: HttpHandler) {
		super(handler);
	}

	options (url: string, options?: any): Observable<any> {
		return super.options(this._fullUrl(url), this._headers(options));
	}

	get (url: string, options?: any): Observable<any> {
		return super.get(this._fullUrl(url), this._headers(options));
	}

	post (url: string, body: string, options?: any): Observable<any> {
		return super.post(this._fullUrl(url), body, this._headers(options));
	}

	put (url: string, body: string, options?: any): Observable<any> {
		return super.put(this._fullUrl(url), body, this._headers(options));
	}

	patch (url: string, body: string, options?: any): Observable<any> {
		return super.patch(this._fullUrl(url), body, this._headers(options));
	}

	delete (url: string, options?: any): Observable<any> {
		return super.delete(this._fullUrl(url), this._headers(options));
	}

	private _fullUrl (url: string): string {
		return environment.api.url + url;
	}

	/**
	 *
	 * @param  any} options
	 *
	 * @return  any}
	 * @private
	 */
	private _headers (options?: any): any {
		//  making sure options & headers are set
		options         = options || {};
		options.headers = options.headers || new HttpHeaders();

		//  client authorization header
		options.headers.append("Api-Client", environment.api.client_token);

		//  add logged in user token
		const user = JSON.parse(localStorage.getItem("user"));

		if (user && user.auth_token) {
			options.headers.append("Authorization", `Basic ${btoa(user.auth_token + ":")}`);
		}

		//  content type headers
		options.headers.append("Content-type", "application/json");
		options.headers.append("Accept", "application/json");

		//  return options with all necessary headers
		return options;
	}

	private _error401 (error: any) {}
}

export function HttpExFactory (xhrBackend: HttpXhrBackend): HttpClient {
	return new HttpEx(xhrBackend);
}

export let HttpExProvider = {
	provide    : HttpClient,
	useFactory : HttpExFactory,
	deps       : [ HttpXhrBackend ],
};

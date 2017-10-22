import { ConnectionBackend, Http, RequestOptions, RequestOptionsArgs, Headers, XHRBackend } from "@angular/http";
import { Injectable } from "@angular/core";
import { environment } from "../../../environments/environment";
import { Observable } from "rxjs/Observable";
import "rxjs/add/operator/toPromise";

@Injectable()
export class HttpEx extends Http {

	constructor (backend: ConnectionBackend, defaultOptions: RequestOptions) {
		super(backend, defaultOptions);
	}

	options (url: string, options?: RequestOptionsArgs): Observable<any> {
		return super.options(this._fullUrl(url), this._headers(options));
	}

	get (url: string, options?: RequestOptionsArgs): Observable<any> {
		return super.get(this._fullUrl(url), this._headers(options));
	}

	post (url: string, body: string, options?: RequestOptionsArgs): Observable<any> {
		return super.post(this._fullUrl(url), body, this._headers(options));
	}

	put (url: string, body: string, options?: RequestOptionsArgs): Observable<any> {
		return super.put(this._fullUrl(url), body, this._headers(options));
	}

	patch (url: string, body: string, options?: RequestOptionsArgs): Observable<any> {
		return super.patch(this._fullUrl(url), body, this._headers(options));
	}

	delete (url: string, options?: RequestOptionsArgs): Observable<any> {
		return super.delete(this._fullUrl(url), this._headers(options));
	}

	private _fullUrl (url: string): string {
		return environment.api.url + url;
	}

	/**
	 *
	 * @param {RequestOptionsArgs} options
	 *
	 * @return {RequestOptionsArgs}
	 * @private
	 */
	private _headers (options?: RequestOptionsArgs): RequestOptionsArgs {
		//  making sure options & headers are set
		options         = options || new RequestOptions();
		options.headers = options.headers || new Headers();

		//  client authorization header
		options.headers.append("Api-Client", environment.api.client_token);

		//  TODO  add logged in user token

		//  content type headers
		options.headers.append("Content-type", "application/json");
		options.headers.append("Accept", "application/json");

		//  return options with all necessary headers
		return options;
	}

	private _error401 (error: any) {}
}

export function HttpExFactory (xhrBackend: XHRBackend, requestOptions: RequestOptions): Http {
	return new HttpEx(xhrBackend, requestOptions);
}

export let HttpExProvider = {
	provide    : Http,
	useFactory : HttpExFactory,
	deps       : [ XHRBackend, RequestOptions ],
};

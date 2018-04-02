import { HttpEvent, HttpHandler, HttpInterceptor, HttpRequest } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { Observable } from "rxjs/Observable";

import { environment } from "env/environment";

@Injectable()
export class AuthInterceptor implements HttpInterceptor {

	intercept ( req: HttpRequest<any>, next: HttpHandler ): Observable<HttpEvent<any>> {
		const reqClone = req.clone({
			url        : AuthInterceptor._fullUrl(req.url),
			setHeaders : {
				"Accept"        : "application/json",
				"Api-Client"    : AuthInterceptor._clientHeader(),
				"Authorization" : AuthInterceptor._authHeader(),
				"Client-type"   : "application/json",
			},
		});

		return next.handle(reqClone);
	}

	/**
	 * Return a fully built url with the API url set in environment file, then with the URL passed in parameter.
	 *
	 * @param {string} url
	 *
	 * @return {string}
	 * @private
	 */
	private static _fullUrl ( url: string ): string {
		return environment.api.url + url;
	}

	/**
	 * Define the Authorization header with the authenticated user auth token.
	 *
	 * @return {string}
	 * @private
	 */
	private static _authHeader () {
		const user = JSON.parse(localStorage.getItem("user"));

		if (user && user.auth_token) {
			return `Basic ${btoa(user.auth_token + ":")}`;
		}

		return "";
	}

	/**
	 * Define the Api Client token key to use
	 *
	 * @return {string}
	 * @private
	 */
	private static _clientHeader () {
		return environment.api.client_token;
	}
}

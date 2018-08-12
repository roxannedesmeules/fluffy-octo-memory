import { HttpEvent, HttpHandler, HttpInterceptor, HttpRequest } from "@angular/common/http";
import { Inject, Injectable, LOCALE_ID } from "@angular/core";
import { Observable } from "rxjs";

import { environment } from "env/environment";

@Injectable()
export class AuthInterceptor implements HttpInterceptor {
    constructor(@Inject(LOCALE_ID) protected localeId: string) {
    }

    /**
     * Return a fully built url with the API url set in environment file, then with the URL passed in parameter.
     *
     * @param {string} url
     *
     * @return {string}
     * @private
     */
    private static fullUrl(url: string): string {
        return environment.api.url + url;
    }

    /**
     * Define the Api Client token key to use
     *
     * @return {string}
     * @private
     */
    private static clientHeader() {
        return environment.api.client_token;
    }

    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        const reqClone = req.clone({
            url        : AuthInterceptor.fullUrl(req.url),
            setHeaders : {
                "Accept"          : "application/json",
                "Api-Client"      : AuthInterceptor.clientHeader(),
                "Accept-Language" : `${this.localeId}-ca`,
                "Client-type"     : "application/json",
            },
        });

        return next.handle(reqClone);
    }
}

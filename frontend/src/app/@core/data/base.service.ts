import { Inject, Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { throwError as observableThrowError, Observable } from "rxjs";
import { catchError, map } from "rxjs/operators";

import { ErrorResponse } from "./error-response.model";

@Injectable()
export abstract class BaseService {
    public http: HttpClient;
    public model: any;
    public modelName: string;
    public baseUrl = "";

    constructor(@Inject(HttpClient) http: HttpClient) {
        this.http = http;
    }

    public findAll(): Observable<any> {
        return this.http.get(this.url(), { observe : "response" })
                   .pipe(
                           map((res: any) => this.mapListToModelList(res.body)),
                           catchError((err: any) => observableThrowError(this.mapError(err))),
                   );
    }

    public findOne(): Observable<any> {
        return this.http.get(this.url())
                   .pipe(
                           map((res: any) => this.mapModel(res)),
                           catchError((err: any) => observableThrowError(this.mapError(err))),
                   );
    }

    public findById(id: any): Observable<any> {
        return this.http.get(this.url(id))
                   .pipe(
                           map((res: any) => this.mapModel(res)),
                           catchError((err: any) => observableThrowError(this.mapError(err))),
                   );
    }

    /**
     *
     * @param {number|string} id
     * @param {string} structure
     *
     * @return {string}
     */
    public url(id?: number | string, structure = ":baseUrl/:modelName/:id"): string {
        let url = structure;
            url = url.replace(":baseUrl", this.baseUrl);
            url = url.replace(":modelName", this.modelName);

        //  replace the ID if one passed
        if (id) {
            url = url.replace(":id", id.toString());
        } else {
            url = url.replace("/:id", "");
        }

        //  replace any slashes at the start or end of a URL
        url = url.replace(/^\//, '');
        url = url.replace(/$\//, '');

        //  return URL
        return url;
    }

    mapError(err) {
        return new ErrorResponse(err.error);
    }

    mapListToModelList(list: any) {
        list.forEach((item, index) => {
            list[ index ] = this.mapModel(item);
        });

        return list;
    }

    mapModel(model: any) {
        return this.model(model);
    }
}

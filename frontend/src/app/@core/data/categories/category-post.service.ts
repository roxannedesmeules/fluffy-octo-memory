import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { throwError as observableThrowError, Observable } from "rxjs";
import { catchError, map } from "rxjs/operators";

import { BaseService } from "@core/data/base.service";
import { CategoryCount } from "./category-count.model";

@Injectable()
export class CategoryPostService extends BaseService {
    public baseUrl   = "categories";
    public modelName = "posts";

    public responseHeaders: HttpHeaders;

    constructor(@Inject(HttpClient) http: HttpClient) {
        super(http);

        this.model = (construct) => new CategoryCount(construct);
    }

    /**
     * Find All
     *
     * return an observable which will return and map a list of CategoryPost
     * models.
     */
    findAll(): Observable<any> {
        return this.http.get(this.url(null, ":baseUrl/:modelName/count"))
                   .pipe(
                           map((res: any) => this.mapListToModelList(res)),
                           catchError((err: any) => observableThrowError(this.mapError(err))),
                   );
    }

    /**
     * Find One
     *
     * return an error since not implemented in API.
     */
    findOne(): Observable<any> {
        return observableThrowError(this.mapError({ error : { code: 501, error: { message: "Not Implemented" } }}));
    }

    /**
     * Find By Id
     *
     * return an error since not implemented in API.
     */
    findById(): Observable<any> {
        return observableThrowError(this.mapError({ error : { code: 501, error: { message: "Not Implemented" } }}));
    }
}

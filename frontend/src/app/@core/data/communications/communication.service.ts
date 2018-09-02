import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { throwError as observableThrowError, Observable } from "rxjs";
import { catchError } from "rxjs/operators";

import { BaseService } from "@core/data/base.service";

@Injectable()
export class CommunicationService extends BaseService {
    modelName = "communications";

    constructor(@Inject(HttpClient) http: HttpClient) {
        super(http);

        this.model = null;
    }

    create(body: any): Observable<any> {
        return this.http
                   .post(this.url(), body)
                   .pipe(catchError((err: any) => observableThrowError(this.mapError(err))));
    }

    findAll(): Observable<any> {
        return observableThrowError(this.mapError({ error : { code: 501, error: { message: "Not Implemented" } }}));
    }

    findById(id: number): Observable<any> {
        return observableThrowError(this.mapError({ error : { code: 501, error: { message: "Not Implemented" } }}));
    }

    findOne(): Observable<any> {
        return observableThrowError(this.mapError({ error : { code: 501, error: { message: "Not Implemented" } }}));
    }
}

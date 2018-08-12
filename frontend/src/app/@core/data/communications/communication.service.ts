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
                   .post(this._url(), body, { observe : "response" })
                   .pipe(catchError((err: any) => observableThrowError(this.mapError(err))));
    }
}

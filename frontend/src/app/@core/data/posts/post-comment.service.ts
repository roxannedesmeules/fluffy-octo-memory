import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { throwError as observableThrowError, Observable } from "rxjs";
import { catchError, map } from "rxjs/operators";

import { BaseService } from "@core/data/base.service";

import { Post } from "./post.model";

@Injectable()
export class PostCommentService extends BaseService {
    baseUrl   = "posts";
    modelName = "comments";

    constructor(@Inject(HttpClient) http: HttpClient) {
        super(http);

        this.model = (construct: any) => new Post(construct);
    }

    createForPost(postId: number, body: any): Observable<any> {
        return this.http.post(this.url(postId, ":baseUrl/:id/:modelName"), body)
                   .pipe(
                           map((res: any) => this.mapModel(res.body)),
                           catchError((err: any) => observableThrowError(this.mapError(err))),
                   );
    }

    /**
     * Find All
     *
     * return an observable error since not implemented in API.
     */
    findAll(): Observable<any> {
        return observableThrowError(this.mapError({ error : { code: 501, error: { message: "Not Implemented" } }}));
    }

    /**
     * Find One
     *
     * return an observable error since not implemented in API.
     */
    findOne(): Observable<any> {
        return observableThrowError(this.mapError({ error : { code: 501, error: { message: "Not Implemented" } }}));
    }

    /**
     * Find by ID
     *
     * return an observable error since not implemented in API.
     */
    findById(): Observable<any> {
        return observableThrowError(this.mapError({ error : { code: 501, error: { message: "Not Implemented" } }}));
    }
}

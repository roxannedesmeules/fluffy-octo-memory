import { HttpClient, HttpHeaders, HttpResponse } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { throwError as observableThrowError, Observable } from "rxjs";
import { catchError, map } from "rxjs/operators";

import { BaseService } from "@core/data/base.service";
import { PostFilters } from "@core/data/posts/post.filters";

import { Post } from "@core/data/posts/post.model";
import { CategoryCount } from "./category-count.model";

@Injectable()
export class CategoryPostService extends BaseService {
    public baseUrl   = "categories";
    public modelName = "posts";

    public responseHeaders: HttpHeaders;

    public filters = new PostFilters();
    public options = {
        observe : "response",
    };

    constructor(@Inject(HttpClient) http: HttpClient) {
        super(http);
    }

    getCount(): Observable<any> {
        return this.http.get(this._url("count"))
                   .pipe(
                           map((res: any) => this.mapToModelList(CategoryCount, res)),
                           catchError((err: any) => observableThrowError(this.mapError(err))),
                   );
    }

    getAll(categorySlug: string): Observable<any> {
        const url = `${this.baseUrl}/${categorySlug}/${this.modelName}`;

        return this.http.get(url, this._getOptions())
                   .pipe(
                           map((res: HttpResponse<Post[]>) => {
                               this.responseHeaders = res.headers;

                               return this.mapToModelList(Post, res.body);
                           }),
                           catchError((err: any) => observableThrowError(this.mapError(err))),
                   );
    }

    mapToModelList(model: any, list: any) {
        list.forEach((item, index) => {
            list[ index ] = new model(item);
        });

        return list;
    }

    protected _getOptions() {
        return Object.assign({}, this.options, this.filters.formatRequest());
    }
}

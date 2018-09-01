import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
import { Category } from "@core/data/categories/category.model";
import { Observable, throwError as observableThrowError } from "rxjs";

@Injectable()
export class CategoryService extends BaseService {
    public modelName = "categories";

    constructor(@Inject(HttpClient) http: HttpClient) {
        super(http);

        this.model = (construct: any) => new Category(construct);
    }

    /**
     * Find One
     *
     * return an observable error since not implemented in API.
     */
    findOne(): Observable<any> {
        return observableThrowError(this.mapError({ error : { code: 501, error: { message: "Not Implemented" } }}));
    }
}

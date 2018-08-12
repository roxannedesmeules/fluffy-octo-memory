import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
import { Category } from "@core/data/categories/category.model";

@Injectable()
export class CategoryService extends BaseService {
    public modelName = "categories";

    constructor(@Inject(HttpClient) http: HttpClient) {
        super(http);

        this.model = (construct: any) => new Category(construct);
    }

}

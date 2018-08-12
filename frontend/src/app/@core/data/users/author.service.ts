import { HttpClient } from "@angular/common/http";
import { Inject, Injectable } from "@angular/core";
import { BaseService } from "@core/data/base.service";
import { Author } from "@core/data/users/author.model";

@Injectable()
export class AuthorService extends BaseService {
    public modelName = "author";

    constructor(@Inject(HttpClient) http: HttpClient) {
        super(http);

        this.model = (construct: any) => new Author(construct);
    }

}

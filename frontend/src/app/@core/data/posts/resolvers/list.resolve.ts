import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve } from "@angular/router";

import { Post } from "../post.model";
import { PostService } from "../post.service";

@Injectable()
export class ListResolve implements Resolve<Post[]> {

    constructor(private service: PostService) {
    }

    resolve(route: ActivatedRouteSnapshot) {
        this.service.filters.set("category", route.paramMap.get("category"));
        this.service.filters.set("tag", route.queryParamMap.get("tag"));

        this.service.filters.setPagination({
            currentPage : route.queryParamMap.get("page"),
            perPage     : route.queryParamMap.get("per-page"),
        });

        return this.service
                   .findAll().toPromise()
                   .then((result: Post[]) => result);
    }
}

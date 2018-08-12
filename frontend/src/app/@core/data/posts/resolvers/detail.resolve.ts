import { Injectable } from "@angular/core";
import { ActivatedRouteSnapshot, Resolve, Router } from "@angular/router";
import { ErrorResponse } from "@core/data/error-response.model";

import { Post } from "../post.model";
import { PostService } from "../post.service";

@Injectable()
export class DetailResolve implements Resolve<Post | boolean> {

    constructor(private _router: Router, private service: PostService) {
    }

    resolve(route: ActivatedRouteSnapshot) {
        return this.service
                   .findById(route.paramMap.get("post")).toPromise()
                   .then((result: Post) => result)
                   .catch((err: ErrorResponse) => {
                       switch (err.code) {
                           case 500 :
                               this._router.navigate([ "/server-failed" ]);
                               break;

                           case 404 :
                               // no break;
                           default :
                               this._router.navigate([ "/not-found" ]);
                               break;
                       }
                       return false;
                   });
    }
}

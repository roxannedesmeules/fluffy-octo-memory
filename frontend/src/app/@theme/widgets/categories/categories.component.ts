import { Component, OnInit } from "@angular/core";
import { Category, CategoryService } from "@core/data/categories";
import { CategoryPostService } from "@core/data/categories/category-post.service";
import { ErrorResponse } from "@core/data/error-response.model";

@Component({
    selector    : "app-widget-categories",
    templateUrl : "./categories.component.html",
    styleUrls   : [ "./categories.component.scss" ],
})
export class CategoriesComponent implements OnInit {

    public categories: Category[];

    constructor(private _categoryService: CategoryService,
                private _categoryPostService: CategoryPostService) {
    }

    ngOnInit() {
        this.getCategoriesWithCount();
    }

    /**
     * Call the Category service and get all categories
     */
    private getCategoriesWithCount() {
        this._categoryService
            .findAll()
            .subscribe(
                    (result: Category[]) => {
                        //  set categories
                        this.categories = result;

                        this.getCategoriesCount();
                    },
                    (err: ErrorResponse) => {
                        console.log(err);
                    },
            );
    }

    /**
     * Call the Category Post service and get the number of posts for every categories
     */
    private getCategoriesCount() {
        //  get post count for all categories
        this._categoryPostService
            .findAll()
            .subscribe((result: any[]) => {
                //  assign the count found to each categories
                this.categories.forEach((cat: Category) => {
                    cat.setPostCount(result);
                });
            });
    }

    public getParams() {
        return { "page" : 0, "per-page" : 10 };
    }
}

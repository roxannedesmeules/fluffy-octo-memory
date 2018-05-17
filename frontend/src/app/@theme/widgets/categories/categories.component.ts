import { Component, OnInit } from "@angular/core";
import { Category, CategoryService } from "@core/data/categories";
import { CategoryPostService } from "@core/data/categories/category-post.service";
import { ErrorResponse } from "@core/data/error-response.model";
import { forkJoin } from "rxjs/observable/forkJoin";

@Component({
	selector    : "app-widget-categories",
	templateUrl : "./categories.component.html",
	styleUrls   : [ "./categories.component.scss" ],
})
export class CategoriesComponent implements OnInit {

	public categories: Category[];

	constructor ( private _categoryService: CategoryService,
				  private _categoryPostService: CategoryPostService ) {
	}

	ngOnInit () {
		this.getCategoriesWithCount();
	}

	/**
	 * Call the Category service and get all categories
	 */
	private getCategoriesWithCount () {
		forkJoin(
				this._categoryService.findAll(),
				this._categoryPostService.getCount(),
		).subscribe(
				( result: any[] ) => {
					this.categories = result[ 0 ];

					this.categories.forEach((cat: Category) => { cat.setPostCount(result[ 1 ]); });
				},
				( err: ErrorResponse ) => {
					console.log(err);
				},
		);
	}
}

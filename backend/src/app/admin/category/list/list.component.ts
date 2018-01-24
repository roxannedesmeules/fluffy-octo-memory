import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Lang } from "@core/data/languages/lang.model";
import { ToasterService } from "angular2-toaster";

import { Category } from "@core/data/categories/category.model";
import { ErrorResponse } from "@core/data/error-response.model";
import { CategoryService } from "@core/data/categories/category.service";

@Component({
	selector    : "ngx-category-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit {

	public categories: Category[];
	public languages: Lang[];
	public statuses: any[] = [ { id : -1, name : "All" }, { id : 0, name : "Inactive" }, { id : 1, name : "Active" }, ];

	constructor ( private _route: ActivatedRoute,
				  private toastService: ToasterService,
				  private service: CategoryService ) { }

	ngOnInit () {
		this.categories = this._route.snapshot.data[ "categories" ];
		this.languages  = this._route.snapshot.data[ "languages" ];
	}

	deleteOne ( categoryId ) {
		this.service
				.delete(categoryId)
				.then(() => {
					this.updateList();
				})
				.catch(( error: ErrorResponse ) => {
					this.toastService.popAsync("error", "Ouch", error.message);
				});
	}

	filter ( attr, value ) {
		this.service.filters.set("active", value);

		this.updateList();
	}

	updateList () {
		this.service
				.findAll()
				.then(( result: any ) => {
					this.categories = this.service.mapListToModelList(result);
				})
				.catch(( error: ErrorResponse ) => {
					this.toastService.popAsync("error", "Ouch", error.message);
				});
	}
}

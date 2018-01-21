import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
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

	public active = -1;

	public list: Category[];

	constructor ( private _route: ActivatedRoute,
				  private toastService: ToasterService,
				  private service: CategoryService ) { }

	ngOnInit () {
		this.list = this._route.snapshot.data[ "list" ];
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

	filter ( active ) {
		this.active = active;

		this.updateList();
	}

	updateList () {
		this.service.filters.active = this.active;

		this.service
				.findAll()
				.then(( result: any ) => {
					this.list = this.service.mapListToModelList(result);
				})
				.catch(( error: ErrorResponse ) => {
					this.toastService.popAsync("error", "Ouch", error.message);
				});
	}
}

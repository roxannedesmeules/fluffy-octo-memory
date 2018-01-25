import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Subscription } from "rxjs/Subscription";
import { ToasterService } from "angular2-toaster";

import { Category } from "@core/data/categories/category.model";
import { Lang } from "@core/data/languages/lang.model";
import { ErrorResponse } from "@core/data/error-response.model";
import { CategoryService } from "@core/data/categories/category.service";
import { Pagination } from "@shared/widgets/pagination/pagination.model";

@Component({
	selector    : "ngx-category-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit {
	private _subscriptions: Subscription[] = [];

	public categories: Category[];
	public languages: Lang[];
	public statuses: any[] = [ { id : -1, name : "All" }, { id : 0, name : "Inactive" }, { id : 1, name : "Active" }, ];

	public pagination: Pagination = new Pagination();

	constructor ( private _route: ActivatedRoute,
				  private toastService: ToasterService,
				  private service: CategoryService ) { }

	ngOnInit () {
		this.pagination.setPagination(this._route.snapshot.data[ "list" ].headers);

		this.categories = this._route.snapshot.data[ "list" ].body;
		this.languages  = this._route.snapshot.data[ "languages" ];

		console.log(this.pagination);

		//  update list on pagination change
		this._subscriptions[ "pagination" ] = this.pagination.getService().subscribe(this.updatePagination);
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
		this.service.filters.set(attr, value);

		this.updateList();
	}

	updatePagination ( data ) {
		this.service.filters.setPagination(this.pagination);

		this.updateList();
	}

	updateList () {
		this.service
				.findAll()
				.then(( result: any ) => {
					//  update pagination information
					this.pagination.setPagination(result.headers);

					//  set categories list
					this.categories = this.service.mapListToModelList(result.body);
				})
				.catch(( error: ErrorResponse ) => {
					this.toastService.popAsync("error", "Ouch", error.message);
				});
	}
}

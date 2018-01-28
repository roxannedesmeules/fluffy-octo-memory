import { Component, OnDestroy, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Subscription } from "rxjs/Subscription";
import { ToasterService } from "angular2-toaster";

import { Category } from "@core/data/categories/category.model";
import { Lang } from "@core/data/languages/lang.model";
import { ErrorResponse } from "@core/data/error-response.model";
import { CategoryService } from "@core/data/categories/category.service";
import { Pagination } from "@shared/widgets/pagination/pagination.model";

@Component( {
	selector: "ngx-category-list",
	templateUrl: "./list.component.html",
	styleUrls: [ "./list.component.scss" ],
} )
export class ListComponent implements OnInit, OnDestroy {
	private _subscriptions: Subscription[] = [];

	public categories: Category[];
	public languages: Lang[];
	public statuses: any[] = [ { id: -1, name: "All" }, { id: 0, name: "Inactive" }, { id: 1, name: "Active" } ];

	public pagination: Pagination = new Pagination();

	constructor ( private _route: ActivatedRoute,
					private toastService: ToasterService,
					private service: CategoryService ) { }

	ngOnInit () {
		this.pagination.setPagination( this._route.snapshot.data[ "list" ].headers );

		this.categories = this._route.snapshot.data[ "list" ].body;
		this.languages = this._route.snapshot.data[ "languages" ];

		//  update list on pagination change
		this._subscriptions[ "pagination" ] = this.pagination
													.getService()
													.subscribe( (data) => { this.updatePagination(data); });
	}

	ngOnDestroy () {
		this._subscriptions[ "pagination" ].removeAll();
	}

	/**
	 * Call service to make API request and delete the record associated to the category ID passed in parameter.
	 *
	 * @param categoryId
	 */
	deleteOne ( categoryId ) {
		this.service
			.delete( categoryId )
			.then( () => {
				this.updateList();
			} )
			.catch( ( error: ErrorResponse ) => {
				this.toastService.popAsync( "error", "Ouch", error.message );
			} );
	}

	/**
	 *
	 * @param attr
	 * @param value
	 */
	filter ( attr, value ) {
		this.service.filters.set( attr, value );

		this.updateList();
	}

	/**
	 * This method is called is time a change is made to the pagination object (current page, page size, ...). The
	 * service filters will be updated accordingly, then the list will be updated.
	 *
	 * @param data
	 */
	updatePagination ( data ) {
		this.service.filters.setPagination( this.pagination );

		this.updateList();
	}

	updateList () {
		this.service
			.findAll()
			.then( ( result: any ) => {
				//  update pagination information
				this.pagination.setPagination( result.headers );

				//  set categories list
				this.categories = this.service.mapListToModelList( result.body );
			} )
			.catch( ( error: ErrorResponse ) => {
				this.toastService.popAsync( "error", "Ouch", error.message );
			} );
	}
}

import { Component, OnDestroy, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Subscription } from "rxjs/Subscription";

import { LoggerService } from "@shared/logger/logger.service";
import { Pagination } from "@shared/pagination/pagination.model";

import { Lang } from "@core/data/languages";
import { Tag, TagService } from "@core/data/tags";
import { ErrorResponse } from "@core/data/error-response.model";

@Component({
	selector    : "app-tag-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit, OnDestroy {
	private _subscriptions: Subscription[] = [];

	public pagination: Pagination = new Pagination();

	public tags: Tag[] = [];
	public languages: Lang[] = [];

	constructor ( private _route: ActivatedRoute,
				  private service: TagService,
				  private logger: LoggerService, ) {}

	ngOnInit () {
		this.tags      = this._route.snapshot.data[ "list" ];
		this.languages = this._route.snapshot.data[ "languages" ];

		this.pagination.setPagination(this.service.responseHeaders);

		this._subscriptions[ "pagination" ] = this.pagination.getService().subscribe((res) => { this.updatePagination(res); });
	}

	ngOnDestroy () {
		this._subscriptions[ "pagination" ].unsubscribe();
	}

	/**
	 * Call service to make API request and delete the record associated to the tag ID passed in parameter.
	 *
	 * @param tagId
	 */
	deleteOne ( tagId ) {
		this.service
			.delete( tagId )
			.subscribe(
					() => {
						this.logger.success("Tag successfully deleted");

						this.updateList();
					},
					(err: ErrorResponse) => {
						this.logger.error(err.shortMessage);
					},
			);
	}

	/**
	 * This method is called when filters are changed. The filter name is passed as first attribute, to allow this
	 * method to be reusable. The service's filters object will be called to update the specific filter and set the
	 * right value. The updateList() method will then be called.
	 *
	 * @param {string} attr
	 * @param value
	 */
	filter ( attr: string, value: any ) {
		this.service.filters.set( attr, value );

		this.updateList();
	}

	filterBy ( attr: string ): boolean {
		return this.service.filters.isSet(attr);
	}

	currentFilter ( attr: string, value: any ): boolean {
		return (this.service.filters[ attr ] === value);
	}

	/**
	 *
	 */
	updateList () {
		this.service
			.findAll()
			.subscribe(
					(result: Tag[]) => {
						this.pagination.setPagination(this.service.responseHeaders);

						this.tags = result;
					},
					(err: ErrorResponse) => {},
			);
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
}

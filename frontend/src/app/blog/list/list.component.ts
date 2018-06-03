import { Component, OnDestroy, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Post, PostService } from "@core/data/posts";
import { Pagination } from "@shared/pagination/pagination.model";
import { Subscription } from "rxjs/Subscription";
import { ErrorResponse } from "../../../../../backend/src/app/@core/data/error-response.model";

@Component({
	selector    : "app-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit, OnDestroy {
	private _subscriptions: Subscription[] = [];

	public pagination: Pagination = new Pagination();
	public list: Post[] = [];

	constructor ( private route: ActivatedRoute, private postService: PostService ) {
	}

	ngOnInit () {
		this.list = this.route.snapshot.data[ "posts" ];

		this.pagination.setPagination(this.postService.responseHeaders);

		this._subscriptions[ "pagination" ] = this.pagination.getService().subscribe((res) => { this.updatePagination(res); });
	}

	ngOnDestroy () {
		this._subscriptions[ "pagination" ].unsubscribe();
	}

	/**
	 *
	 */
	updateList () {
		this.postService
			.findAll()
			.subscribe(
					(result: Post[]) => {
						this.pagination.setPagination(this.postService.responseHeaders);

						this.list = result;
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
		this.postService.filters.setPagination( this.pagination );

		this.updateList();
	}
}

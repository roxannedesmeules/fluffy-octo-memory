import { Component, OnInit, OnDestroy } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { ErrorResponse } from "@core/data/error-response.model";
import { Lang } from "@core/data/languages";
import { Post, PostService } from "@core/data/posts";
import { LoggerService } from "@shared/logger/logger.service";
import { Pagination } from "@shared/pagination/pagination.model";
import { Subscription } from "rxjs/Subscription";

@Component({
	selector    : "app-post-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit, OnDestroy {
	private _subscriptions: Subscription[] = [];

	public pagination: Pagination = new Pagination();

	public posts: Post[] = [];
	public languages: Lang[] = [];

	constructor (private _route: ActivatedRoute,
				 private service: PostService,
				 private logger: LoggerService) {
	}

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
	 * Call service to make API request and delete the record associated to the post ID passed in parameter.
	 *
	 * @param postId
	 */
	deleteOne ( postId ) {
		this.service
			.delete( postId )
			.subscribe(
					() => {
						this.logger.success("Post successfully deleted");

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

	/**
	 *
	 */
	updateList () {
		this.service
			.findAll()
			.subscribe(
					(result: Post[]) => {
						this.pagination.setPagination(this.service.responseHeaders);

						this.posts = result;
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

import { Component, OnDestroy, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { ErrorResponse } from "@core/data/error-response.model";
import { Lang } from "@core/data/languages/lang.model";
import { ToasterService } from "angular2-toaster";

import { PostService } from "@core/data/posts/post.service";
import { Post } from "@core/data/posts/post.model";
import { PostStatus } from "@core/data/posts/post-status.model";
import { Pagination } from "@shared/widgets/pagination/pagination.model";
import { Subscription } from "rxjs/Subscription";

@Component({
	selector    : "ngx-post-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit, OnDestroy {
	private _subscriptions: Subscription[] = [];

	public posts: Post[];
	public statuses: PostStatus[];
	public languages: Lang[];

	public pagination: Pagination = new Pagination();

	constructor ( private _route: ActivatedRoute,
					private toastService: ToasterService,
					private service: PostService ) { }

	ngOnInit () {
		// get headers from ListResolver to set pagination
		this.pagination.setPagination(this._route.snapshot.data[ "list" ].headers);

		// set all property from the route resolvers
		this.posts     = this._route.snapshot.data[ "list" ].body;
		this.statuses  = this._route.snapshot.data[ "statuses" ];
		this.languages = this._route.snapshot.data[ "languages" ];

		// add "all" options to languages filters
		this.languages.unshift({ id: -1, icu : "all", name: "All", native : "All" });

		// listen to any change to the pagination
		this._subscriptions[ "pagination" ] = this.pagination
													.getService()
													.subscribe((data) => this.updatePagination(data));
	}

	ngOnDestroy () {
		this._subscriptions[ "pagination" ].unsubscribe();
	}

	/**
	 * this method uses the PostService to make API call and delete a single post.
	 *
	 * @param {number} postId
	 */
	public deleteOne ( postId: number ) {
		this.service
				.delete(postId)
				.then(() => {
					this.updateList();
				})
				.catch(( error: ErrorResponse ) => {
					this.toastService.popAsync("error", "Ouch", error.message);
				});
	}

	public filter ( attr: string, value: any ) {
		this.service.filters.set(attr, value);

		this.updateList();
	}

	private updatePagination ( data: object) {
		this.service.filters.setPagination(data);

		this.updateList();
	}

	public updateList () {
		this.service
			.findAll()
			.then(( result: any ) => {
				this.pagination.setPagination(result.headers);

				this.posts = this.service.mapListToModelList(result.body);
			})
			.catch(( error: ErrorResponse ) => {
				this.toastService.popAsync("error", "Ouch", error.message);
			});
	}
}

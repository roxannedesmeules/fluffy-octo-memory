import { Component, OnDestroy, OnInit } from "@angular/core";
import { ActivatedRoute, NavigationEnd, Router } from "@angular/router";
import { Category } from "@core/data/categories";
import { Post, PostService } from "@core/data/posts";
import { Tag } from "@core/data/tags";
import { Pagination } from "@shared/pagination/pagination.model";
import { Subscription } from "rxjs/Subscription";

@Component({
	selector    : "app-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit, OnDestroy {
	private _subscriptions: Subscription[] = [];

	public pagination: Pagination = new Pagination();
	public list: Post[] = [];

	public category: Category = null;
	public tag: Tag = null;

	constructor ( private router: Router,
				  private route: ActivatedRoute,
				  private postService: PostService) {
	}

	ngOnInit () {
		this.initPosts();

		this._subscriptions[ "pagination" ] = this.pagination.getService().subscribe((res) => { this.updatePagination(res); });
		this._subscriptions[ "navigation" ] = this.router.events.subscribe((ev: any) => {
			if (ev instanceof NavigationEnd) {
				this.initPosts();
			}
		});
	}

	ngOnDestroy () {
		this._subscriptions[ "pagination" ].unsubscribe();
	}

	/**
	 * This method is called when the component is initialized or when a route is updated. This will set the post list
	 * from the resolver result and the pagination accordingly.
	 */
	initPosts () {
		this.list     = this.route.snapshot.data[ "posts" ];
		this.category = this.route.snapshot.data[ "category" ] || null;
		this.tag      = this.route.snapshot.data[ "tag" ] || null;

		this.pagination.setPagination(this.postService.responseHeaders);
	}

	/**
	 * This method is called each time a change is made to the pagination object (current page, page size, ...). The
	 * service filters will be updated accordingly, then the list will be updated.
	 *
	 * @param data
	 */
	updatePagination ( data ) {
		this.router.navigate([ "/blog" ], { queryParams : { page : data.currentPage, "per-page" : data.perPage } });
	}
}

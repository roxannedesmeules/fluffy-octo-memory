import { Component, OnInit, OnDestroy } from "@angular/core";
import { ActivatedRoute, NavigationEnd, Router } from "@angular/router";
import { Subscription } from "rxjs/Subscription";
import { ErrorResponse } from "../../../../../backend/src/app/@core/data/error-response.model";
import { Category, CategoryPostService } from "../../@core/data/categories";
import { Post } from "../../@core/data/posts";
import { Pagination } from "../../@shared/pagination/pagination.model";

@Component({
	selector    : "app-blog-category",
	templateUrl : "./category.component.html",
	styleUrls   : [ "./category.component.scss" ],
})
export class CategoryComponent implements OnInit, OnDestroy {
	private _subscriptions: Subscription[] = [];

	public pagination: Pagination = new Pagination();

	public list: Post[] = [];
	public category: Category = new Category();

	constructor ( private route: ActivatedRoute, private router: Router, private service: CategoryPostService ) {
	}

	ngOnInit () {
		this.initPosts();

		this._subscriptions[ "pagination" ] = this.pagination.getService().subscribe((res) => { this.updatePagination(res); });
		this._subscriptions[ "navigation" ] = this.router.events.subscribe((e) => {
			if (e instanceof NavigationEnd) {
				this.initPosts();
			}
		});
	}

	ngOnDestroy () {
		this._subscriptions[ "pagination" ].unsubscribe();
	}

	initPosts () {
		this.category = this.route.snapshot.data[ "category" ];
		this.list     = this.route.snapshot.data[ "posts" ];

		this.pagination.setPagination(this.service.responseHeaders);
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
		this.service.filters.setPagination( this.pagination );

		this.updateList();
	}
}

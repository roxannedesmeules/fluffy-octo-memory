import { Component, OnDestroy, OnInit, TemplateRef, ViewChild } from "@angular/core";
import { Meta, Title } from "@angular/platform-browser";
import { ActivatedRoute, NavigationEnd, Router } from "@angular/router";
import { ErrorResponse } from "@core/data/error-response.model";
import { Subscription } from "rxjs/Subscription";

import { Category, CategoryService } from "@core/data/categories";
import { Post, PostService } from "@core/data/posts";
import { Tag, TagService } from "@core/data/tags";
import { Pagination } from "@shared/pagination/pagination.model";

@Component({
	selector    : "app-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit, OnDestroy {
	private subscriptions: Subscription[] = [];

	public pagination: Pagination = new Pagination();
	public list: Post[]           = [];
	public waitingList: Post[]    = [];

	public category: Category = null;
	public tag: Tag           = null;
	public search: string     = null;

	public waitingRelation: boolean = true;
	public waitingPosts: boolean    = true;

	@ViewChild('metadataTranslation') metadataTranslation: TemplateRef<any>;

	constructor ( private router: Router,
				  private route: ActivatedRoute,
				  private title: Title,
				  private meta: Meta,
				  private categoryService: CategoryService,
				  private postService: PostService,
				  private tagService: TagService ) {
	}

	ngOnInit () {
		this.setMetadata();
		this._initData();

		this.subscriptions[ "pagination" ] = this.pagination.getService().subscribe(( res ) => {
			this.updatePagination(res);
		});
		this.subscriptions[ "navigation" ] = this.router.events.subscribe(( ev: any ) => {
			if (ev instanceof NavigationEnd) {
				this._initData();
			}
		});
	}

	ngOnDestroy () {
		this.subscriptions[ "pagination" ].unsubscribe();
		this.subscriptions[ "navigation" ].unsubscribe();
	}

	/**
	 * This method will handle any error returned by a load request.
	 * 404 and 500 errors will be redirected, others will simply be printed in the console.
	 *
	 * @param {ErrorResponse} err
	 * @private
	 */
	private _handleError (err: ErrorResponse) {
		switch (err.code) {
			case 404 :
				this.router.navigate([ "/not-found" ]);
				break;

			case 500 :
				this.router.navigate([ "/server-failed" ]);
				break;

			default :
				console.log(err);
				break;
		}
	}

	/**
	 * Initialize the page data. This will get the category and tag details if necessary and load all posts according to
	 * filters.
	 *
	 * @private
	 */
	private _initData () {
		this.waitingPosts    = true;

		const tagSlug = this.route.snapshot.queryParamMap.get("tag");
		const catSlug = this.route.snapshot.paramMap.get("category");

		this.waitingRelation = (catSlug || tagSlug) ? true : false;

		const search = this.route.snapshot.queryParamMap.get("search");
		this.search  = (search) ? search : null;

		this._loadPosts();
		this._loadCategory();
		this._loadTag();
	}

	/**
	 * Get the category detail if there is a category in the URL.
	 *
	 * @private
	 */
	private _loadCategory () {
		this.category = null;
		const catSlug = this.route.snapshot.paramMap.get("category");

		if (!catSlug) {
			return;
		}

		this.categoryService
			.findById(catSlug)
			.subscribe(( result: Category ) => {
				this.waitingRelation = false;
				this.category        = result;

				this.title.setTitle(this.category.name + " - ladydev.io");
			}, this._handleError);
	}

	/**
	 * Get a list of posts.
	 *
	 * @private
	 */
	private _loadPosts () {
		this.list        = [];
		this.waitingList = [
			new Post(), new Post(),
			new Post(), new Post(),
			new Post(), new Post(),
			new Post(), new Post(),
			new Post(), new Post(),
		];

		this.postService.filters.reset();

		this.postService.filters.set("category", this.route.snapshot.paramMap.get("category"));
		this.postService.filters.set("tag", this.route.snapshot.queryParamMap.get("tag"));
		this.postService.filters.set("search", this.route.snapshot.queryParamMap.get("search"));

		this.postService.filters.setPagination({
			currentPage : this.route.snapshot.queryParamMap.get("page"),
			perPage     : this.route.snapshot.queryParamMap.get("per-page"),
		});

		this.postService
			.findAll()
			.subscribe(( result: Post[] ) => {
				this.waitingPosts = false;
				this.list         = result;

				this.pagination.setPagination(this.postService.responseHeaders);
			}, this._handleError);
	}

	/**
	 * Get the tag detail if there is a tag in the URL.
	 *
	 * @private
	 */
	private _loadTag () {
		this.tag      = null;
		const tagSlug = this.route.snapshot.queryParamMap.get("tag");

		if (!tagSlug) {
			return;
		}

		this.tagService
			.findById(tagSlug)
			.subscribe(( result: Tag ) => {
				this.waitingRelation = false;
				this.tag             = result;

				this.title.setTitle(this.tag.name + " - ladydev.io");
			}, this._handleError);
	}

	/**
	 * Set page meta data and title
	 */
	private setMetadata() {
		this.title.setTitle("ladydev.io");

		const nodes = this.metadataTranslation.createEmbeddedView({}).rootNodes

		this.meta.updateTag({ name: "description", content: nodes[1].innerText }, "name='description'");
	}

	/**
	 * This method is called each time a change is made to the pagination object (current page, page size, ...). The
	 * service filters will be updated accordingly, then the list will be updated.
	 *
	 * @param data
	 */
	private updatePagination ( data ) {
		const params = {
			queryParams : { page : data.currentPage, "per-page" : data.perPage }
		};

		this.router.navigate([ "/blog" ], params);
	}
}

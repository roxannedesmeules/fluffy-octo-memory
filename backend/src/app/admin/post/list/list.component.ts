import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { ErrorResponse } from "@core/data/error-response.model";
import { Lang } from "@core/data/languages/lang.model";
import { ToasterService } from "angular2-toaster";

import { PostService } from "@core/data/posts/post.service";
import { Post } from "@core/data/posts/post.model";
import { PostStatus } from "@core/data/posts/post-status.model";

@Component({
	selector    : "ngx-post-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit {

	public posts: Post[];
	public statuses: PostStatus[];
	public languages: Lang[];

	public filters = {
		status : {},
	};

	constructor ( private _route: ActivatedRoute,
				  private toastService: ToasterService,
				  private service: PostService ) { }

	ngOnInit () {
		this.posts     = this._route.snapshot.data[ "posts" ];
		this.statuses  = this._route.snapshot.data[ "statuses" ];
		this.languages = this._route.snapshot.data[ "languages" ];
	}

	public deleteOne ( postId ) {
		this.service
				.delete(postId)
				.then(() => {
					this.updateList();
				})
				.catch(( error: ErrorResponse ) => {
					this.toastService.popAsync("error", "Ouch", error.message);
				});
	}

	public updateList () {
		this.service
				.findAll()
				.then(( result: any ) => {
					this.posts = this.service.mapListToModelList(result);
				})
				.catch(( error: ErrorResponse ) => {
					this.toastService.popAsync("error", "Ouch", error.message);
				});
	}

	public filter ( attr, statusId ) {
		this.service.filters.set(attr, statusId);

		this.updateList();
	}
}

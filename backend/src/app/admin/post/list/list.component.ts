import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
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

	constructor ( private _route: ActivatedRoute,
				  private toastService: ToasterService,
				  private service: PostService ) { }

	ngOnInit () {
		this.posts    = this._route.snapshot.data[ "posts" ];
		this.statuses = this._route.snapshot.data[ "statuses" ];
	}

}

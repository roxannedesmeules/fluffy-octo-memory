import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Post, PostService } from "@core/data/posts";
import { Pagination } from "@shared/pagination/pagination.model";

@Component({
	selector    : "app-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit {

	public pagination: Pagination = new Pagination();
	public list: Post[];

	constructor ( private route: ActivatedRoute, private postService: PostService ) {
	}

	ngOnInit () {
		this.list = this.route.snapshot.data[ "posts" ];

		this.pagination.setPagination(this.postService.responseHeaders);
	}
}

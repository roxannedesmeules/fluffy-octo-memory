import { Component, OnInit } from "@angular/core";
import { Post, PostService } from "@core/data/posts";

@Component({
	selector    : "app-list",
	templateUrl : "./list.component.html",
	styleUrls   : [ "./list.component.scss" ],
})
export class ListComponent implements OnInit {

	public list: Post[];

	constructor ( private _postService: PostService ) {
	}

	ngOnInit () {
		this._getPosts();
	}

	private _getPosts () {
		this._postService
			.findAll()
			.subscribe(
					(result: Post[]) => {
						this.list = result;
					},
			);
	}
}

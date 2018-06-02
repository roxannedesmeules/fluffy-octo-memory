import { Component, OnInit } from "@angular/core";
import { Post, PostService } from "@core/data/posts";

@Component({
	selector    : "app-home",
	templateUrl : "./home.component.html",
	styleUrls   : [ "./home.component.scss" ],
})
export class HomeComponent implements OnInit {

	public latests: Post[] = [];

	constructor (private postService: PostService) {
	}

	ngOnInit () {
		this._getLatestPosts();
	}

	private _getLatestPosts () {
		this.postService
			.latests()
			.subscribe((result: Post[]) => { this.latests = result; });
	}
}

import { Component, Inject, LOCALE_ID, OnInit } from "@angular/core";
import { Post, PostService } from "@core/data/posts";

@Component({
	selector    : "app-home",
	templateUrl : "./home.component.html",
	styleUrls   : [ "./home.component.scss" ],
})
export class HomeComponent implements OnInit {

	public latests: Post[] = [];
	public featured: Post[] = [];

	constructor (@Inject(LOCALE_ID) protected locale: string,
				 private postService: PostService) {
	}

	ngOnInit () {
		console.log(this.locale);
		this._getFeaturedPost();
		this._getLatestPosts();
	}

	private _getLatestPosts () {
		this.postService
			.latests()
			.subscribe((result: Post[]) => { this.latests = result; });
	}

	private _getFeaturedPost () {
		this.postService
			.featured()
			.subscribe((result: Post[]) => { this.featured = result; });
	}
}

import { Component, OnInit } from "@angular/core";
import { Post, PostService } from "@core/data/posts";
import { environment } from "../../../../environments/environment";

@Component({
	selector    : "app-layout-footer",
	templateUrl : "./footer.component.html",
	styleUrls   : [ "./footer.component.scss" ],
})
export class FooterComponent implements OnInit {

	public latests: Post[];

	public year = 2018;
	public socialMedia = environment.socialMedia;

	constructor (private postService: PostService) {
	}

	ngOnInit () {
		this._setCurrentYear();
		this._getLatestPosts();
	}

	private _setCurrentYear() {
		this.year = (new Date()).getFullYear();
	}

	private _getLatestPosts () {
		this.postService
			.latests()
			.subscribe((result: Post[]) => { this.latests = result; });
	}
}

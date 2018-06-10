import { Component } from "@angular/core";
import { Router } from "@angular/router";

@Component({
	selector    : "app-layout-blog",
	templateUrl : "./blog.component.html",
	styleUrls   : [ "./blog.component.scss" ],
})
export class BlogComponent {

	constructor (private router: Router) {
	}

	currentPageList (): boolean {
		let isBlog = this.router.url.includes("/blog");
		let isPost = ((this.router.url.match(/\//g) || []).length === 3);

		return (isBlog && !isPost);
	}
}

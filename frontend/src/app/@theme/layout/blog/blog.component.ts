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

	currentPageIs ( route: string ): boolean {
		return this.router.isActive(route, true);
	}
}

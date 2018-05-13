import { Component, Input } from "@angular/core";

@Component({
	selector    : "app-home-featured-post",
	templateUrl : "./featured-post.component.html",
	styleUrls   : [ "./featured-post.component.scss" ],
})
export class FeaturedPostComponent {

	@Input()
	public post;

	@Input()
	public imgSide: string;

	constructor () {
	}

	displayImage ( side: string ): boolean {
		return (this.imgSide === side);
	}
}

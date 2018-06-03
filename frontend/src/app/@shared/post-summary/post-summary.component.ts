import { Component, Input, OnInit } from "@angular/core";
import { Post } from "@core/data/posts";

@Component({
	selector    : "app-post-summary",
	templateUrl : "./post-summary.component.html",
	styleUrls   : [ "./post-summary.component.scss" ],
})
export class PostSummaryComponent implements OnInit {

	@Input() post: Post;
	@Input() format: string = "full";

	constructor () {
	}

	ngOnInit () {
	}

	public showAuthor () {
		return (this.format === "full");
	}
}

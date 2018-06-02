import { Component, Input, OnInit } from "@angular/core";
import { Post } from "@core/data/posts";

@Component({
	selector    : "app-home-latest-post",
	templateUrl : "./latest-post.component.html",
	styleUrls   : [ "./latest-post.component.scss" ],
})
export class LatestPostComponent implements OnInit {

	@Input() post: Post = new Post();

	constructor () {
	}

	ngOnInit () {
	}

}

import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Post } from "@core/data/posts";

@Component({
	selector    : "app-blog-post",
	templateUrl : "./post.component.html",
	styleUrls   : [ "./post.component.scss" ],
})
export class PostComponent implements OnInit {

	public post: Post;

	constructor (private route: ActivatedRoute) {
	}

	ngOnInit () {
		this.post = this.route.snapshot.data[ "post" ];
	}

	public updatePost ( post: Post ) {
		this.post = post;
	}
}

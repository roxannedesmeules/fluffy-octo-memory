import { Component, OnInit } from "@angular/core";
import { Meta, Title } from "@angular/platform-browser";
import { ActivatedRoute } from "@angular/router";
import { Post } from "@core/data/posts";

@Component({
	selector    : "app-blog-post",
	templateUrl : "./post.component.html",
	styleUrls   : [ "./post.component.scss" ],
})
export class PostComponent implements OnInit {

	public post: Post;

	constructor (private meta: Meta,
				 private title: Title,
				 private route: ActivatedRoute) {
	}

	ngOnInit () {
		this.post = this.route.snapshot.data[ "post" ];

		this.setMetadata();
	}

	public setMetadata() {
		this.title.setTitle(this.post.title);

		this.meta.updateTag({ name: "title", content: this.post.title }, "name='title'");
		this.meta.updateTag({ name: "description", content: this.post.getSummary() }, "name='description'");
	}

	public updatePost ( post: Post ) {
		this.post = post;
	}
}

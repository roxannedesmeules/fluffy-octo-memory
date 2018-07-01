import { Component, EventEmitter, Input, OnInit, Output } from "@angular/core";
import { Post, PostComment } from "@core/data/posts";

@Component({
	selector    : "app-blog-post-comments",
	templateUrl : "./comment.component.html",
	styleUrls   : [ "./comment.component.scss" ],
})
export class CommentComponent implements OnInit {

	@Input() postId: number;
	@Input() count: number;
	@Input() comments: PostComment[];

	@Output() onCreate: EventEmitter<Post> = new EventEmitter<Post>();

	constructor () {
	}

	ngOnInit () {
	}

	passToParent ( $event ) {
		this.onCreate.next($event);
	}
}

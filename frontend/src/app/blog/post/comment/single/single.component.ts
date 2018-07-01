import { Component, EventEmitter, Input, OnInit, Output } from "@angular/core";
import { Post, PostComment } from "@core/data/posts";

@Component({
	selector    : "app-blog-post-comment",
	templateUrl : "./single.component.html",
	styleUrls   : [ "./single.component.scss" ],
})
export class SingleComponent implements OnInit {

	@Input() postId: number;
	@Input() comment: PostComment;
	@Output() onCreate: EventEmitter<Post> = new EventEmitter<Post>();

	public showForm = false;

	constructor () {
	}

	ngOnInit () {
	}

	passToParent ( $event ) {
		this.showForm = false;

		this.onCreate.next($event);
	}
}

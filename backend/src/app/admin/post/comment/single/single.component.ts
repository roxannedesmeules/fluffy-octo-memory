import { Component, EventEmitter, Input, OnInit, Output } from "@angular/core";
import { P } from "@angular/core/src/render3";
import { ErrorResponse } from "@core/data/error-response.model";
import { PostComment, PostCommentService } from "@core/data/posts";

@Component({
	selector    : "app-post-comment-single",
	templateUrl : "./single.component.html",
	styleUrls   : [ "./single.component.scss" ],
})
export class SingleComponent implements OnInit {

	@Input() comment: PostComment;
	@Output() onUpdate: EventEmitter<PostComment[]> = new EventEmitter<PostComment[]>();

	constructor ( private service: PostCommentService ) {
	}

	ngOnInit () {
	}

	public toggleApprobation () {
		const body = {
			is_approved : (this.comment.isNotApproved()) ? PostComment.IS_APPROVED : PostComment.NOT_APPROVED
		};

		this.service
				.updateForPost(this.comment.post_id, this.comment.id, body)
				.subscribe(
						(result: PostComment[]) => {
							this.onUpdate.emit(result);
						},
						(err: ErrorResponse) => { console.log(err); }
				);
	}

	public sendToParent ( $event ) {
		this.onUpdate.emit($event);
	}
}

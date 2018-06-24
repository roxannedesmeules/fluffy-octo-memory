import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { PostComment, PostCommentService } from "@core/data/posts";
import { NgbActiveModal } from "@ng-bootstrap/ng-bootstrap";

@Component({
	selector    : "app-post-comment-reply",
	templateUrl : "./reply.component.html",
	styleUrls   : [ "./reply.component.scss" ],
})
export class ReplyComponent implements OnInit {

	public comment: PostComment;
	public form: FormGroup;

	constructor ( private activeModal: NgbActiveModal,
				  private builder: FormBuilder,
				  private commentService: PostCommentService) {
	}

	ngOnInit () {
		this._createForm();
	}

	public close () {
		this.activeModal.close();
	}

	private _createForm () {
		this.form = this.builder.group({
			reply_comment_id : this.builder.control(this.comment.id),
			comment          : this.builder.control("", [ Validators.required ]),
		});
	}

	public save () {
		this.commentService
				.createForPost(this.comment.post_id, this.form.getRawValue())
				.subscribe(
						(result: PostComment[]) => {
							this.activeModal.close(result);
						}
				)
	}
}

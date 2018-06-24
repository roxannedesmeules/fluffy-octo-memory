import { Component, EventEmitter, Input, OnInit, Output } from "@angular/core";
import { ErrorResponse } from "@core/data/error-response.model";
import { PostComment, PostCommentService } from "@core/data/posts";
import { NgbModal, NgbModalOptions } from "@ng-bootstrap/ng-bootstrap";
import { ReplyComponent } from "admin/post/comment/reply/reply.component";

@Component({
	selector    : "app-post-comment-single",
	templateUrl : "./single.component.html",
	styleUrls   : [ "./single.component.scss" ],
})
export class SingleComponent implements OnInit {

	@Input() comment: PostComment;
	@Output() onUpdate: EventEmitter<PostComment[]> = new EventEmitter<PostComment[]>();

	constructor ( private _modal: NgbModal, private service: PostCommentService ) {
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

	public openEdit () {
		const options = {
			size     : "lg",
			centered : true,
		} as NgbModalOptions;

		const instance = this._modal.open(ReplyComponent, options);

		instance.componentInstance.isUpdate = true;
		instance.componentInstance.comment = this.comment;
	}

	public openReply () {
		const options = {
			size     : "lg",
			centered : true,
		} as NgbModalOptions;

		const instance = this._modal.open(ReplyComponent, options);

		instance.componentInstance.isUpdate = false;
		instance.componentInstance.comment  = this.comment;

		instance
				.result
				.then((result: any) => {
					if (result != undefined) {
						this.onUpdate.emit(result);
					}
				}).catch((err) => { console.log(err); });
	}

	public sendToParent ( $event ) {
		this.onUpdate.emit($event);
	}
}

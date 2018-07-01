import { Component, EventEmitter, Input, OnInit, Output } from "@angular/core";
import { FormBuilder, FormGroup } from "@angular/forms";
import { Post, PostCommentService } from "@core/data/posts";

@Component({
	selector    : "app-blog-post-comment-form",
	templateUrl : "./form.component.html",
	styleUrls   : [ "./form.component.scss" ],
})
export class FormComponent implements OnInit {

	@Input() replyTo: number = null;
	@Input() postId: number;
	@Output() onCreate: EventEmitter<Post> = new EventEmitter<Post>();

	public form: FormGroup;

	constructor (private _builder: FormBuilder, private service: PostCommentService) {
	}

	ngOnInit () {
		this._buildForm();
	}

	private _buildForm () {
		this.form = this._builder.group({
			author           : this._builder.control(""),
			email            : this._builder.control(""),
			reply_comment_id : this._builder.control(this.replyTo),
			comment          : this._builder.control(""),
		});
	}

	public createComment () {
		this.service.createForPost(this.postId, this.form.getRawValue())
			.subscribe(
					(result: Post) => {
						this.onCreate.next(result);
					},
					(err: any) => {
						console.log(err);
					}
			);
	}
}

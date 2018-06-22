import { Component, OnInit } from "@angular/core";
import { ActivatedRoute } from "@angular/router";
import { Lang } from "@core/data/languages";
import { PostComment } from "@core/data/posts";

@Component({
	selector    : "app-comment",
	templateUrl : "./comment.component.html",
	styleUrls   : [ "./comment.component.scss" ],
})
export class CommentComponent implements OnInit {

	public languages: Lang[];
	public comments: any;

	constructor ( private _route: ActivatedRoute ) {
	}

	ngOnInit () {
		this._setData();
	}

	protected getComments ( lang: string ): PostComment[] {
		return this.comments[ lang ] || [];
	}

	/**
	 * The list of comments is updated with the one passed in parameter by the event object. This method is called by
	 * the single comment component after an update.
	 *
	 * @param $event
	 */
	public reloadList ( $event ) {
		this.comments = $event;
	}

	private _setData () {
		this.languages = this._route.snapshot.data[ "languages" ];
		this.comments  = this._route.snapshot.data[ "comments" ];
	}
}

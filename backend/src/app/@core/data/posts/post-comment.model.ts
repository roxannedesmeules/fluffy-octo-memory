import { Author, UserService } from "@core/data/users";

export class PostComment {
	public static IS_APPROVED  = 1;
	public static NOT_APPROVED = 0;

	public id: number;
	public post_id: number;
	public lang_id: number;

	public user: Author;

	public author: string;
	public comment: string;

	public is_approved: number = PostComment.NOT_APPROVED;

	public created_on: string;
	public approved_on: string;

	public replies: PostComment[] = [];

	constructor ( model: any = null ) {
		if (!model) return;

		this.id      = model.id;
		this.post_id = model.post_id;
		this.lang_id = model.lang_id;

		this.user = new Author(model.user);

		this.author  = model.author;
		this.comment = model.comment;

		this.is_approved = model.is_approved;

		this.created_on  = model.created_on;
		this.approved_on = model.approved_on;

		this.replies = this.mapListOfReplies(model.replies);
	}


	protected mapListOfReplies ( list: any[] ): PostComment[] {
		list.forEach((val, idx) => {
			list[ idx ] = new PostComment(val);
		});

		return list;
	}

	public isApproved (): boolean {
		return (this.is_approved === PostComment.IS_APPROVED);
	}

	public isNotApproved (): boolean {
		return (this.is_approved === PostComment.NOT_APPROVED);
	}

	public isOwner (): boolean {
		if (this.user === null) {
			return false;
		}

		const user = JSON.parse(localStorage.getItem(UserService.STORAGE_KEY));

		return (this.user.id == user.id);
	}
}
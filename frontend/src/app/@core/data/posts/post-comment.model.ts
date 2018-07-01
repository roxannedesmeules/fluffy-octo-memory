export class PostComment {
	public id: number;
	public author: string;
	public comment: string;
	public replies: PostComment[];
	public created_on: string;

	constructor ( model: any = null ) {
		if (!model) return;

		this.id         = model.id;
		this.author     = model.author;
		this.comment    = model.comment;
		this.replies    = this._mapReplies(model.replies);
		this.created_on = model.created_on;
	}

	/**
	 * This method will take a list of comments and map them to a PostComment class.
	 *
	 * @param {any[]} list
	 *
	 * @return {PostComment[]}
	 * @private
	 */
	protected _mapReplies ( list: any[] ): PostComment[] {
		list.forEach((val, idx) => {
			list[ idx ] = new PostComment(val);
		});

		return list;
	}

	/**
	 * This method will check if this comment has replies.
	 *
	 * @return {boolean}
	 */
	public hasReplies () {
		return (this.replies.length > 0);
	}
}
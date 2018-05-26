import { Category } from "@core/data/categories";
import { PostCover } from "@core/data/posts/post-cover.model";
import { Author } from "@core/data/users";

export class Post {
	public id: number;
	public category: Category;
	public title: string;
	public slug: string;
	public content: string;
	public cover: PostCover;
	public author: Author;

	constructor ( model: any = null ) {
		if (!model) {
			return;
		}

		this.id       = model.id;
		this.category = new Category(model.category);
		this.title    = model.title;
		this.slug     = model.slug;
		this.content  = model.content;
		this.cover    = new PostCover(model.cover);
		this.author   = new Author(model.author);
	}
}
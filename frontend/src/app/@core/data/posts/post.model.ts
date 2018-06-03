import { Category } from "@core/data/categories/category.model";
import { PostCover } from "@core/data/posts/post-cover.model";
import { Tag } from "@core/data/tags/tag.model";
import { Author } from "@core/data/users/author.model";

export class Post {
	public id: number;
	public category: Category;
	public title: string;
	public slug: string;
	public summary: string;
	public content: string;
	public cover: PostCover;
	public tags: Tag[];
	public author: Author;
	public published_on: string;

	constructor ( model: any = null ) {
		if (!model) {
			return;
		}

		this.id       = model.id;
		this.category = new Category(model.category);
		this.title    = model.title;
		this.slug     = model.slug;
		this.summary  = model.summary;
		this.content  = model.content || "";
		this.cover    = new PostCover(model.cover);
		this.tags     = (model.tags) ? this.mapListToModelList(Tag, model.tags) : [];
		this.author   = new Author(model.author);
		this.published_on = model.published_on;
	}

	private mapListToModelList ( model, list: any[] ) {
		list.forEach((val: any, idx: number) => {
			list[ idx ] = new model(val);
		});

		return list;
	}

	public getUrl (): string {
		return  "/blog/" + this.category.slug + "/" + this.slug;
	}
}
import { Category } from "@core/data/categories/category.model";
import { PostComment } from "@core/data/posts/post-comment.model";
import { PostCover } from "@core/data/posts/post-cover.model";
import { Tag } from "@core/data/tags/tag.model";
import { Author } from "@core/data/users/author.model";

export class Post {
	public static NOT_FEATURED     = 0;
	public static FEATURED         = 1;
	public static COMMENTS_ENABLED = 1;

	public id: number = null;
	public category: Category = null;
	public featured: number        = Post.NOT_FEATURED;
	public comment_enabled: number = Post.COMMENTS_ENABLED;
	public title: string = "";
	public slug: string = "";
	public summary: string = "";
	public content: string = "";
	public cover: PostCover = null;
	public tags: Tag[] = [];
	public comments: any = [];
	public author: Author = null;
	public published_on: string = "";

	constructor ( model: any = null ) {
		if (!model) {
			return;
		}

		this.id              = model.id;
		this.featured        = parseInt(model.featured);
		this.comment_enabled = parseInt(model.comment_enabled);

		this.title        = model.title;
		this.slug         = model.slug;
		this.summary      = model.summary;
		this.content      = model.content || "";

		this.cover        = new PostCover(model.cover);
		this.category     = new Category(model.category);
		this.tags         = (model.tags) ? this.mapListToModelList(Tag, model.tags) : [];
		this.author       = new Author(model.author);

		this.published_on = model.published_on;

		this.comments     = {
			count : model.comments.count,
			list  : (model.comments.list) ? this.mapListToModelList(PostComment, model.comments.list) : [],
		};
	}

	private mapListToModelList ( model, list: any[] ) {
		list.forEach(( val: any, idx: number ) => {
			list[ idx ] = new model(val);
		});

		return list;
	}

	public getUrl (): string {
		if (this.id === null) {
			return "";
		}

		return "/blog/" + this.category.slug + "/" + this.slug;
	}

	public commentsAreEnabled (): boolean {
		return (this.comment_enabled === Post.COMMENTS_ENABLED);
	}
}
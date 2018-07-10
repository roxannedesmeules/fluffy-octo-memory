import { PostCommentService } from "@core/data/posts/post-comment.service";
import { PostCoverService } from "@core/data/posts/post-cover.service";
import { PostStatusService } from "@core/data/posts/post-status.service";
import { PostTagService } from "@core/data/posts/post-tag.service";
import { PostService } from "@core/data/posts/post.service";

import { CommentResolve, DetailResolve, ListResolve, StatusResolve } from "@core/data/posts/resolvers";

// posts
export * from "./post-lang.model";
export * from "./post.model";
export * from "./post.filters";
export * from "./post.service";

// post cover
export * from "./post-cover.model";
export * from "./post-cover.service";

// post status
export * from "./post-status.model";
export * from "./post-status.service";

// post tag relation
export * from "./post-tag.service";

// post comments
export * from "./post-comment.model";
export * from "./post-comment.service";

// resolvers
export * from "./resolvers";

export const SERVICES = [
		PostService,
		PostCoverService,
		PostStatusService,
		PostTagService,
		PostCommentService,

		CommentResolve,
		DetailResolve,
		ListResolve,
		StatusResolve,
];
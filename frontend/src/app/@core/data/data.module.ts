import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import {
	CategoryService,
	CategoryPostService,
	DetailResolve as CategoryDetailResolve
} from "@core/data/categories";
import {
	PostService,
	DetailResolve as PostDetailResolve,
	ListResolve as PostListResolve,
	PostCommentService,
} from "@core/data/posts";
import { TagService, DetailResolve as TagDetailResolve } from "@core/data/tags";

const SERVICES = [
	CategoryService,
	CategoryPostService,
	CategoryDetailResolve,

	TagService,
	TagDetailResolve,

	PostService,
	PostCommentService,
	PostListResolve,
	PostDetailResolve,
];

@NgModule({
	imports   : [
		CommonModule,
	],
	providers : [
		...SERVICES,
	],
})
export class DataModule {
	static forRoot (): ModuleWithProviders {
		return <ModuleWithProviders>{
			ngModule  : DataModule,
			providers : [
				...SERVICES,
			],
		};
	}
}

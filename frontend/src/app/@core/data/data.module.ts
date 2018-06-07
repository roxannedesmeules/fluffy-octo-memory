import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import {
	CategoryPostService,
	CategoryService,
	DetailResolve as CategoryDetailResolve
} from "@core/data/categories";
import { PostService, DetailResolve as PostDetailResolve, ListResolve as PostListResolve } from "@core/data/posts";
import { TagService } from "@core/data/tags";

const SERVICES = [
	CategoryService,
	CategoryPostService,
	CategoryDetailResolve,

	TagService,

	PostService,
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

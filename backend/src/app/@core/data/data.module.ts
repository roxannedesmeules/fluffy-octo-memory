import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { LangService, LanguageResolve } from "@core/data/languages";
import {
	CategoryService,
	DetailResolve as CategoryDetailResolve,
	PartialListResolve as CategoryPartialListResolve,
	FullListResolve as CategoryFullListResolve,
} from "@core/data/categories";
import {
	PostService,
	DetailResolve as PostDetailResolve,
	ListResolve as PostListResolve,
	PostStatusService,
	StatusResolve, PostCoverService,
} from "@core/data/posts";
import {
	TagService,
	DetailResolve as TagDetailResolve,
	PartialListResolve as TagPartialListResolve,
	FullListResolve as TagFullListResolve
} from "@core/data/tags";
import { AuthService, UserService, UserProfileService } from "@core/data/users";

const SERVICES = [
	LangService,
	LanguageResolve,

	CategoryService,
	CategoryDetailResolve,
	CategoryPartialListResolve,
	CategoryFullListResolve,

	TagService,
	TagDetailResolve,
	TagPartialListResolve,
	TagFullListResolve,

	PostService,
	PostCoverService,
	PostDetailResolve,
	PostListResolve,
	PostStatusService,
	StatusResolve,

	AuthService,
	UserService,
	UserProfileService,
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

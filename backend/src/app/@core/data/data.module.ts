import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { LangService, LanguageResolve } from "@core/data/languages";
import {
	CategoryService,
	DetailResolve as CategoryDetailResolve,
	ListResolve as CategoryListResolve } from "@core/data/categories";
import {
	PostService,
	DetailResolve as PostDetailResolve,
	ListResolve as PostListResolve } from "@core/data/posts";
import {
	TagService,
	DetailResolve as TagDetailResolve,
	ListResolve as TagListResolve } from "@core/data/tags";
import { AuthService, UserService, UserProfileService } from "@core/data/users";

const SERVICES = [
	LangService,
	LanguageResolve,

	CategoryService,
	CategoryDetailResolve,
	CategoryListResolve,

	TagService,
	TagDetailResolve,
	TagListResolve,

	PostService,
	PostDetailResolve,
	PostListResolve,

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

import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { PostService } from "@core/data/posts/post.service";
import { AuthService } from "@core/data/users/auth.service";
import { UserProfileService } from "@core/data/users/user-profile.service";
import { UserService } from "@core/data/users/user.service";

import { LangService, LanguageResolve } from "@core/data/languages";
import {
	CategoryService,
	DetailResolve as CategoryDetailResolve,
	ListResolve as CategoryListResolve } from "@core/data/categories";

const SERVICES = [
	LangService,
	LanguageResolve,

	CategoryService,
	CategoryDetailResolve,
	CategoryListResolve,

	PostService,

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

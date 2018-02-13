import { CommonModule } from "@angular/common";
import { NgModule, ModuleWithProviders } from "@angular/core";

import { AuthService } from "@core/data/users/auth.service";
import { CategoryService } from "@core/data/categories/category.service";
import { LangService } from "@core/data/languages/lang.service";
import { PostService } from "@core/data/posts/post.service";
import { PostStatusService } from "@core/data/posts/post-status.service";
import { UserProfileService } from "@core/data/users/user-profile.service";
import { UserService } from "@core/data/users/user.service";

const SERVICES = [
	// categories
	CategoryService,

	// languages
	LangService,

	// posts
	PostService,
	PostStatusService,

	// user's services
	AuthService,
	UserService,
	UserProfileService,
];

@NgModule({
	imports   : [
		CommonModule,
	],
	providers : [
		... SERVICES,
	],
})
export class DataModule {
	static forRoot (): ModuleWithProviders {
		return <ModuleWithProviders>{
			ngModule  : DataModule,
			providers : [
				... SERVICES,
			],
		};
	}
}

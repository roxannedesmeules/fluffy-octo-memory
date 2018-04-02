import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { CategoryService } from "@core/data/categories/category.service";
import { LangService } from "@core/data/languages/lang.service";
import { PostService } from "@core/data/posts/post.service";
import { AuthService } from "@core/data/users/auth.service";
import { UserProfileService } from "@core/data/users/user-profile.service";
import { UserService } from "@core/data/users/user.service";

const SERVICES = [
	LangService,

	CategoryService,

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

import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { CategoryPostService, CategoryService } from "@core/data/categories";
import { PostService, DetailResolve } from "@core/data/posts";
import { TagService } from "@core/data/tags";

const SERVICES = [
	CategoryService,
	CategoryPostService,

	TagService,

	PostService,
	DetailResolve,
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

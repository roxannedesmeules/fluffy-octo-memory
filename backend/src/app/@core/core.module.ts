import { ModuleWithProviders, NgModule, Optional, SkipSelf } from "@angular/core";
import { CommonModule } from "@angular/common";

import { RegularAuthGuard } from "@core/guards/regular-auth.guard";
import { throwIfAlreadyLoaded } from "./module-import-guard";

import { DataModule } from "./data/data.module";
import { AnalyticsService } from "./utils/analytics.service";

import { AuthService } from "@core/data/users/auth.service";
import { UserService } from "@core/data/users/user.service";
import { CategoryService } from "@core/data/categories/category.service";

const NB_CORE_PROVIDERS = [
	... DataModule.forRoot().providers,
	AnalyticsService,
	RegularAuthGuard,

	AuthService,
	UserService,
	CategoryService,
];

@NgModule({
	imports      : [
		CommonModule,
	],
	exports      : [],
	declarations : [],
})
export class CoreModule {
	constructor (@Optional() @SkipSelf() parentModule: CoreModule) {
		throwIfAlreadyLoaded(parentModule, "CoreModule");
	}

	static forRoot (): ModuleWithProviders {
		return <ModuleWithProviders>{
			ngModule  : CoreModule,
			providers : [
				... NB_CORE_PROVIDERS,
			],
		};
	}
}

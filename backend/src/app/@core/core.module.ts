import { HttpClientModule } from "@angular/common/http";
import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { RegularAuthGuard } from "@core/guards/regular-auth.guard";
import { DataModule } from "./data/data.module";

const PROVIDERS = [
	...DataModule.forRoot().providers,

	RegularAuthGuard,
];

@NgModule({
	imports      : [
		CommonModule,
		HttpClientModule,
	],
	declarations : [],
})
export class CoreModule {
	static forRoot (): ModuleWithProviders {
		return <ModuleWithProviders>{
			ngModule  : CoreModule,
			providers : [
				... PROVIDERS,
			],
		};
	}
}

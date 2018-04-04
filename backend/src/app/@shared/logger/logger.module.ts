import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ToastrModule } from "ngx-toastr";
import { LoggerService } from "./logger.service";

const PROVIDERS = [
	LoggerService,
];

@NgModule({
	imports      : [
		CommonModule,
		ToastrModule.forRoot(),
	],
	declarations : [],
})
export class LoggerModule {
	static forRoot (): ModuleWithProviders {
		return <ModuleWithProviders>{
			ngModule  : LoggerModule,
			providers : [
				... PROVIDERS,
			],
		};
	}
}

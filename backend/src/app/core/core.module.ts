import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { LayoutModule } from "./layout/layout.module";
import { SessionModule } from "./session/session.module";

@NgModule({
	imports      : [
		//  angular
		CommonModule,

		//  core modules
		LayoutModule,
		SessionModule,
	],
	declarations : [],
	exports      : [
		LayoutModule,
		SessionModule,
	],
})
export class CoreModule {
}

import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { LoggerComponent } from "./logger.component";
import { ToastyModule } from "ng2-toasty";

@NgModule({
	imports      : [
		CommonModule,

		ToastyModule.forRoot(),
	],
	declarations : [ LoggerComponent ],
	providers    : [ LoggerComponent ],
	exports      : [ LoggerComponent ],
})
export class LoggerModule {}

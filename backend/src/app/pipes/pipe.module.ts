import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { AtIndexOfPipe } from "./array/at-index-of.pipe";

@NgModule({
	imports      : [
		CommonModule,
	],
	declarations : [ AtIndexOfPipe ],
	exports      : [ AtIndexOfPipe ],
})
export class PipeModule {}

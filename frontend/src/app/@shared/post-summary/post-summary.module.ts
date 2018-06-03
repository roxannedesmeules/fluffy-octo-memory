import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { RouterModule } from "@angular/router";
import { PostSummaryComponent } from "@shared/post-summary/post-summary.component";

@NgModule({
	imports      : [
		CommonModule,
		RouterModule,
	],
	declarations : [ PostSummaryComponent ],
	exports      : [ PostSummaryComponent ],
})
export class PostSummaryModule {
}

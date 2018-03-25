import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { BlogRoutingModule } from "./blog-routing.module";
import { TagsComponent } from "./tags/tags.component";
import { TutorialComponent } from "./tutorial/tutorial.component";

const COMPONENTS = [
	TagsComponent,
	TutorialComponent,
];

@NgModule({
	imports      : [
		CommonModule,
		BlogRoutingModule,
	],
	declarations : [ ...COMPONENTS ],
})
export class BlogModule {
}

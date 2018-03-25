import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { BlogRoutingModule } from "./blog-routing.module";
import { TagsComponent } from "./tutorial/tags/tags.component";
import { TutorialComponent } from "./tutorial/tutorial.component";
import { FooterComponent } from './tutorial/footer/footer.component';

const COMPONENTS = [
	FooterComponent,
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

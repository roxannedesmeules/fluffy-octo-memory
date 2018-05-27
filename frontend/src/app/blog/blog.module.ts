import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { PipesModule } from "@shared/pipes/pipes.module";

import { BlogRoutingModule } from "./blog-routing.module";
import { ListComponent } from "./list/list.component";
import { TagsComponent } from "./post/tags/tags.component";
import { FooterComponent } from "./post/footer/footer.component";
import { PostComponent } from './post/post.component';

const COMPONENTS = [
	ListComponent,
	FooterComponent,
	PostComponent,
	TagsComponent,
];

@NgModule({
	imports      : [
		CommonModule,
		BlogRoutingModule,
		PipesModule,
	],
	declarations : [ ...COMPONENTS ],
})
export class BlogModule {
}

import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ThemeModule } from "@theme/theme.module";
import { PipesModule } from "@shared/pipes/pipes.module";
import { PostRoutingModule } from "@admin/post/post-routing.module";

import { PostComponent } from "./post.component";
import { ListComponent } from "./list/list.component";
import { DetailComponent } from "./detail/detail.component";

@NgModule({
	imports      : [
		CommonModule,
		ThemeModule,
		PostRoutingModule,
		PipesModule,
	],
	declarations : [
		PostComponent,
		ListComponent,
		DetailComponent,
	],
})
export class PostModule {}

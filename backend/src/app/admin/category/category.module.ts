import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ThemeModule } from "@theme/theme.module";
import { CategoryRoutingModule } from "@admin/category/category-routing.module";
import { PipesModule } from "@shared/pipes/pipes.module";
import { WidgetsModule } from "@shared/widgets/widgets.module";

import { CategoryComponent } from "./category.component";
import { ListComponent } from "./list/list.component";
import { DetailComponent } from "./detail/detail.component";

@NgModule({
	imports      : [
		CommonModule,
		ThemeModule,
		CategoryRoutingModule,
		PipesModule,
		WidgetsModule,
	],
	declarations : [
		CategoryComponent,
		ListComponent,
		DetailComponent,
	],
})
export class CategoryModule {}

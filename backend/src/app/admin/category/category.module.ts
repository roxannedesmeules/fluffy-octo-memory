import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { PaginationModule } from "@shared/pagination/pagination.module";
import { PipesModule } from "@shared/pipes/pipes.module";
import { ThemeModule } from "@theme/theme.module";
import { DetailComponent } from "admin/category/detail/detail.component";

import { CategoryRoutingModule } from "./category-routing.module";

import { CategoryComponent } from "admin/category/category.component";
import { ListComponent } from "admin/category/list/list.component";

@NgModule({
	imports      : [
		CommonModule,
		FormsModule,
		ReactiveFormsModule,

		ThemeModule,
		PipesModule,
		PaginationModule,

		CategoryRoutingModule,
	],
	declarations : [
		CategoryComponent,
		ListComponent,
		DetailComponent,
	],
})
export class CategoryModule {
}

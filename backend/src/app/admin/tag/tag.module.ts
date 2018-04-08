import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";

import { TagRoutingModule } from "./tag-routing.module";

import { PaginationModule } from "@shared/pagination/pagination.module";
import { PipesModule } from "@shared/pipes/pipes.module";
import { ThemeModule } from "@theme/theme.module";

import { TagComponent } from "admin/tag/tag.component";
import { ListComponent } from "admin/tag/list/list.component";
import { DetailComponent } from "admin/tag/detail/detail.component";


@NgModule({
	imports      : [
		CommonModule,
		FormsModule,
		ReactiveFormsModule,
		TagRoutingModule,

		PaginationModule,
		PipesModule,
		ThemeModule,
	],
	declarations : [
		TagComponent,
		ListComponent,
		DetailComponent,
	],
})
export class TagModule {
}

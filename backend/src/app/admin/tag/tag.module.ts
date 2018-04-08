import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { TagRoutingModule } from "./tag-routing.module";

import { PaginationModule } from "@shared/pagination/pagination.module";
import { PipesModule } from "@shared/pipes/pipes.module";

import { TagComponent } from "admin/tag/tag.component";
import { ListComponent } from "admin/tag/list/list.component";
import { DetailComponent } from "admin/tag/detail/detail.component";


@NgModule({
	imports      : [
		CommonModule,
		TagRoutingModule,

		PaginationModule,
		PipesModule,
	],
	declarations : [
		TagComponent,
		ListComponent,
		DetailComponent,
	],
})
export class TagModule {
}

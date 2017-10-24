import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { CategoriesRoutingModule } from "components/categories/categories-routing.module";
import { ListComponent } from "./list/list.component";
import { CategoriesService } from "services/categories/categories.service";

@NgModule({
	imports      : [
		CommonModule,
		CategoriesRoutingModule,
	],
	declarations : [
		ListComponent,
	],
	providers    : [
		CategoriesService,
	],
})
export class CategoriesModule {}

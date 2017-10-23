import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ListComponent } from "./list/list.component";
import { CategoriesRoutingModule } from "components/categories/categories-routing.module";

@NgModule({
	imports      : [
		CommonModule,
		CategoriesRoutingModule,
	],
	declarations : [ ListComponent ],
})
export class CategoriesModule {}

import { CategoryRoutingModule } from "@admin/category/category-routing.module";
import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { SmartTableService } from "@core/data/smart-table.service";
import { ThemeModule } from "@theme/theme.module";
import { Ng2SmartTableModule } from "ng2-smart-table";
import { CategoryComponent } from "./category.component";
import { ListComponent } from "./list/list.component";

@NgModule({
	imports      : [
		CommonModule,
		ThemeModule,
		CategoryRoutingModule,
	],
	declarations : [
		CategoryComponent,
		ListComponent,
	],
})
export class CategoryModule {}

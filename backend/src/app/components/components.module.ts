import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { DashboardModule } from "./dashboard/dashboard.module";
import { CategoriesModule } from "components/categories/categories.module";

@NgModule({
	imports      : [
		//  angular modules
		CommonModule,

		//  components modules
		DashboardModule,
		CategoriesModule,
	],
	declarations : [ ],
})
export class ComponentsModule {
}

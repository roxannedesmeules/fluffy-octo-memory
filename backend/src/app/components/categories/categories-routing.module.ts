import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { AppContentComponent } from "core/layout/app-content/app-content.component";
import { ListComponent } from "components/categories/list/list.component";
import { AuthGuard } from "../../shared/helpers/guards/auth.guard";

const routes: Routes = [
	{
		path        : "",
		component   : AppContentComponent,
		canActivate : [ AuthGuard ],
		children    : [
			{ path : "categories", component : ListComponent },
		],
	},
];

@NgModule({
	imports : [ RouterModule.forChild(routes) ],
	exports : [ RouterModule ],
})
export class CategoriesRoutingModule {}

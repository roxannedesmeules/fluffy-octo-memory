import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { RegularAdminPageComponent } from "core/layout/regular-admin-page/regular-admin-page.component";
import { ListComponent } from "components/categories/list/list.component";
import { AuthGuard } from "../../shared/helpers/guards/auth.guard";
import { ListResolve } from "components/categories/list/list.resolve";

const routes: Routes = [
	{
		path        : "",
		component   : RegularAdminPageComponent,
		canActivate : [ AuthGuard ],
		children    : [
			{ path : "categories", component : ListComponent, resolve : { list : ListResolve } },
		],
	},
];

@NgModule({
	imports   : [ RouterModule.forChild(routes) ],
	exports   : [ RouterModule ],
	providers : [ ListResolve ]
})
export class CategoriesRoutingModule {}

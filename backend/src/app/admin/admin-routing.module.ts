import { RouterModule, Routes } from "@angular/router";
import { NgModule } from "@angular/core";
import { RegularAuthGuard } from "@core/guards/regular-auth.guard";

import { AdminComponent } from "@admin/admin.component";
import { DashboardComponent } from "@admin/dashboard/dashboard.component";

const routes: Routes = [
	{
		path        : "",
		component   : AdminComponent,
		canActivate : [ RegularAuthGuard ],
		children    : [
			{
				path      : "dashboard",
				component : DashboardComponent,
			}, {
				path         : "categories",
				loadChildren : "",
			}, {
				path       : "",
				redirectTo : "dashboard",
				pathMatch  : "full",
			},
		],
	},
];

@NgModule({
	imports : [ RouterModule.forChild(routes) ],
	exports : [ RouterModule ],
})
export class AdminRoutingModule {
}

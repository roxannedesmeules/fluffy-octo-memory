import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { DashboardComponent } from "./dashboard.component";
import { AuthGuard } from "../../shared/helpers/guards/auth.guard";
import { CustomAdminPageComponent } from "core/layout/custom-admin-page/custom-admin-page.component";

const routes: Routes = [
	{
		path        : "",
		component   : CustomAdminPageComponent,
		canActivate : [ AuthGuard ],
		children    : [
			{ path : "", component : DashboardComponent, },
		],
	},
];

@NgModule({
	imports : [ RouterModule.forChild(routes) ],
	exports : [ RouterModule ],
})
export class DashboardRoutingModule {
}

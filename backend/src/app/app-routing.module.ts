import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

const routes: Routes = [
	{
		path         : "auth",
		loadChildren : "./session/session.module#SessionModule",
	},
	/*{
		path         : "admin",
		loadChildren : "./admin/admin.module#AdminModule",
	},*/

	// { path : "", redirectTo : "admin/dashboard", pathMatch : "full" },
];

@NgModule({
	imports : [ RouterModule.forRoot(routes) ],
	exports : [ RouterModule ],
})
export class AppRoutingModule {
}

import { ExtraOptions, RouterModule, Routes } from "@angular/router";
import { NgModule } from "@angular/core";

const routes: Routes = [
	{
		path         : "auth",
		loadChildren : "app/session/session.module#SessionModule",
	},
	{
		path         : "admin",
		loadChildren : "app/admin/admin.module#AdminModule",
	},

	{ path : "", redirectTo : "admin/dashboard", pathMatch : "full" },
];

const config: ExtraOptions = {
	useHash : false,
};

@NgModule({
	imports : [ RouterModule.forRoot(routes, config) ],
	exports : [ RouterModule ],
})
export class AppRoutingModule {
}

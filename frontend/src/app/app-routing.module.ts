import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { HomeComponent } from "./@theme/layout";

const routes: Routes = [
	{
		path      : "",
		component : HomeComponent,
		children  : [
			{
				path         : "",
				loadChildren : "./home/home.module#HomeModule",
				pathMatch    : "full",
			},
		],
	},
];

@NgModule({
	imports : [ RouterModule.forRoot(routes) ],
	exports : [ RouterModule ],
})
export class AppRoutingModule {
}

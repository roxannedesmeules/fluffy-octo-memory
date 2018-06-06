import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { BlogComponent, HomeComponent } from "./@theme/layout";

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
	}, {
		path      : "blog",
		component : BlogComponent,
		children  : [
			{
				path         : "",
				loadChildren : "./blog/blog.module#BlogModule",
			},
		],
	},
];

@NgModule({
	imports : [ RouterModule.forRoot(routes, { onSameUrlNavigation : "reload" }) ],
	exports : [ RouterModule ],
})
export class AppRoutingModule {
}

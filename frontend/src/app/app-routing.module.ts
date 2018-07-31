import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { BlogComponent as BlogLayout, HomeComponent as HomeLayout } from "./@theme/layout";

const routes: Routes = [
	{
		path      : "",
		component : HomeLayout,
		children  : [
			{
				path         : "",
				loadChildren : "./home/home.module#HomeModule",
				pathMatch    : "full",
			}, {
				path         : "**",
				loadChildren : "./error/error.module#ErrorModule",
			},
		],
	}, {
		path      : "",
		component : BlogLayout,
		children  : [
			{
				path         : "blog",
				loadChildren : "./blog/blog.module#BlogModule",
			}, {
				path         : "contact",
				loadChildren : "./contact/contact.module#ContactModule",
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

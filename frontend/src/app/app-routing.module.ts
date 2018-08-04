import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { BlogComponent as BlogLayout, HomeComponent as HomeLayout, AppComponent as AppLayout } from "./@theme/layout";

const routes: Routes = [
	{
		path      : "",
		component : AppLayout,
		children  : [
			{
				path      : "",
				component : HomeLayout,
				children  : [
					{
						path         : "",
						loadChildren : "./home/home.module#HomeModule",
						pathMatch    : "full",
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
			}, {
				path      : "",
				component : HomeLayout,
				children  : [
					{
						path         : "",
						loadChildren : "./error/error.module#ErrorModule",
					},
				],
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

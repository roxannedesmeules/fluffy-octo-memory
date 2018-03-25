import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { LayoutComponent } from "./@theme/layout/layout.component";

const routes: Routes = [
	{
		path      : "",
		component : LayoutComponent,
		children  : [
			{
				path         : "tutorials",
				loadChildren : "app/blog/blog.module#BlogModule",
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

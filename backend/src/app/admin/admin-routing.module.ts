import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { RegularAuthGuard } from "@core/guards/regular-auth.guard";

import { AdminComponent } from "admin/admin.component";
import { DashboardComponent } from "admin/dashboard/dashboard.component";

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
				loadChildren : "./category/category.module#CategoryModule",
			}, {
				path         : "tags",
				loadChildren : "./tag/tag.module#TagModule",
			}, {
				path         : "posts",
				loadChildren : "./post/post.module#PostModule",
			}, {
				path         : "user",
				loadChildren : "./user/user.module#UserModule",
			}, {
				path         : "messages",
				loadChildren : "./communication/communication.module#CommunicationModule",
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

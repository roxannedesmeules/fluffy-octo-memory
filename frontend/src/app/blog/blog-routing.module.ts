import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import { DetailResolve } from "@core/data/posts";

import { ListComponent } from "./list/list.component";
import { PostComponent } from "./post/post.component";

const routes: Routes = [
	{
		path      : "",
		component : ListComponent,
		runGuardsAndResolvers : "always",
	}, {
		path      : ":category",
		component : ListComponent,
		runGuardsAndResolvers : "always",
	}, {
		path      : ":category/:post",
		component : PostComponent,
		resolve   : {
			post : DetailResolve,
		},
	},
];

@NgModule({
	imports : [ RouterModule.forChild(routes) ],
	exports : [ RouterModule ],
})
export class BlogRoutingModule {
}

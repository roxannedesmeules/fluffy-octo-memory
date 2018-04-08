import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import { LanguageResolve } from "@core/data/languages";
import { ListResolve, DetailResolve } from "@core/data/posts";

import { PostComponent } from "admin/post/post.component";
import { ListComponent } from "admin/post/list/list.component";
import { DetailComponent } from "admin/post/detail/detail.component";

const routes: Routes = [
	{
		path      : "",
		component : PostComponent,
		children  : [
			{
				path      : "",
				component : ListComponent,
				resolve   : {
					list      : ListResolve,
					languages : LanguageResolve,
				},
			}, {
				path      : "create",
				component : DetailComponent,
				resolve   : {
					languages : LanguageResolve,
				},
			}, {
				path      : "update/:id",
				component : DetailComponent,
				resolve   : {
					post      : DetailResolve,
					languages : LanguageResolve,
				},
			},
		],
	},
];

@NgModule({
	imports : [ RouterModule.forChild(routes) ],
	exports : [ RouterModule ],
})
export class PostRoutingModule {
}

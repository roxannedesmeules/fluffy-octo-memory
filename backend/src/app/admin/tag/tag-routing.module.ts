import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import { LanguageResolve } from "@core/data/languages";
import { DetailResolve, ListResolve } from "@core/data/tags";

import { TagComponent } from "admin/tag/tag.component";
import { ListComponent } from "admin/tag/list/list.component";
import { DetailComponent } from "admin/tag/detail/detail.component";

const routes: Routes = [
	{
		path      : "",
		component : TagComponent,
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
					tag       : DetailResolve,
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
export class TagRoutingModule {
}

import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import { ListResolve } from "@core/data/categories";
import { LanguageResolve } from "@core/data/languages";

import { CategoryComponent } from "admin/category/category.component";
import { DetailComponent } from "admin/category/detail/detail.component";
import { ListComponent } from "admin/category/list/list.component";


const routes: Routes = [
	{
		path      : "",
		component : CategoryComponent,
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
			},
		],
	},
];

@NgModule({
	imports : [ RouterModule.forChild(routes) ],
	exports : [ RouterModule ],
})
export class CategoryRoutingModule {
}

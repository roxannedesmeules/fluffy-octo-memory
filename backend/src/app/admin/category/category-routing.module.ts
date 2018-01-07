import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { CategoryComponent } from "@admin/category/category.component";
import { DetailComponent } from "@admin/category/detail/detail.component";
import { ListComponent } from "@admin/category/list/list.component";

import { ListResolve } from "@admin/category/list/list.resolve";
import { LanguageResolve } from "@shared/resolver/language.resolve";

const RESOLVERS = [
	ListResolve,
	LanguageResolve,
];

const routes: Routes = [
	{
		path      : "",
		component : CategoryComponent,
		children  : [
			{
				path      : "list",
				component : ListComponent,
				resolve   : { list : ListResolve },
			}, {
				path      : "create",
				component : DetailComponent,
				resolve   : { languages : LanguageResolve },
			},
		],
	},
];

@NgModule({
	imports   : [ RouterModule.forChild(routes) ],
	exports   : [ RouterModule ],
	providers : [
		... RESOLVERS,
	],
})
export class CategoryRoutingModule {
}

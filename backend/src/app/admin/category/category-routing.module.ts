import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { CategoryComponent } from "@admin/category/category.component";
import { DetailComponent } from "@admin/category/detail/detail.component";
import { ListComponent } from "@admin/category/list/list.component";

import { ListResolve } from "@shared/resolver/category/list.resolve";
import { DetailResolve } from "@shared/resolver/category/detail.resolve";
import { LanguageResolve } from "@shared/resolver/language.resolve";

const RESOLVERS = [
	DetailResolve,
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
			}, {
				path      : "update/:id",
				component : DetailComponent,
				resolve   : {
					languages : LanguageResolve,
					category  : DetailResolve,
				},
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

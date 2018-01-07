import { CategoryComponent } from "@admin/category/category.component";
import { ListComponent } from "@admin/category/list/list.component";
import { ListResolve } from "@admin/category/list/list.resolve";
import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

const RESOLVERS = [
	ListResolve,
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
			}, /*{
			 path : "create",
			 },*/
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

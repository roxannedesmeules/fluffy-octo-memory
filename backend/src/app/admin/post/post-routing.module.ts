import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { PostComponent } from "@admin/post/post.component";
import { ListComponent } from "@admin/post/list/list.component";
import { DetailComponent } from "@admin/post/detail/detail.component";

import { DetailResolve } from "@shared/resolver/post/detail.resolve";
import { LanguageResolve } from "@shared/resolver/language.resolve";
import { ListResolve } from "@shared/resolver/post/list.resolve";
import { ListResolve as StatusListResolve } from "@shared/resolver/post/status/list.resolve";
import { ListResolve as CategoryListResolve } from "@shared/resolver/category/list.resolve";

const RESOLVERS = [
	LanguageResolve,
	ListResolve,
	DetailResolve,

	StatusListResolve,
	CategoryListResolve,
];

const routes: Routes = [
	{
		path      : "",
		component : PostComponent,
		children  : [
			{
				path      : "list",
				component : ListComponent,
				resolve   : {
					posts     : ListResolve,
					statuses  : StatusListResolve,
					languages : LanguageResolve,
				},
			}, {
				path      : "create",
				component : DetailComponent,
				resolve   : {
					categories : CategoryListResolve,
					languages  : LanguageResolve,
					statuses   : StatusListResolve,
				},
			}, {
				path      : "update/:id",
				component : DetailComponent,
				resolve   : {
					categories : CategoryListResolve,
					languages  : LanguageResolve,
					post       : DetailResolve,
					statuses   : StatusListResolve,
				},
			},
		],
	},
];

@NgModule({
	imports   : [ RouterModule.forChild(routes) ],
	exports   : [ RouterModule ],
	providers : [ ... RESOLVERS ],
})
export class PostRoutingModule {}

import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import {
	DetailResolve as CategoryDetailResolve,
	PostListResolve as CategoryPostListResolve,
} from "@core/data/categories";
import { ListResolve, DetailResolve } from "@core/data/posts";

import { ListComponent } from "./list/list.component";
import { PostComponent } from "./post/post.component";

const routes: Routes = [
	{
		path      : "",
		component : ListComponent,
		resolve   : {
			posts : ListResolve,
		},
	}, {
		path      : ":category",
		component : ListComponent,
		resolve   : {
			category : CategoryDetailResolve,
			posts    : CategoryPostListResolve,
		},
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

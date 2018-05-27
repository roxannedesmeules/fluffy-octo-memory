import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { DetailResolve } from "@core/data/posts";
import { ListResolve } from "@core/data/posts/resolvers/list.resolve";
import { ListComponent } from "./list/list.component";
import { PostComponent } from "./post/post.component";

const routes: Routes = [
	{
		path      : "",
		component : ListComponent,
		resolve   : {
			posts : ListResolve,
		}
	}, {
		path      : ":category/:post",
		component : PostComponent,
		resolve   : {
			post : DetailResolve,
		}
	},

];

@NgModule({
	imports : [ RouterModule.forChild(routes) ],
	exports : [ RouterModule ],
})
export class BlogRoutingModule {
}

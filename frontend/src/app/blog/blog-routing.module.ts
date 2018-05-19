import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { ListComponent } from "./list/list.component";
import { TutorialComponent } from "./tutorial/tutorial.component";

const routes: Routes = [
	{
		path      : "",
		component : ListComponent,
	},

];

@NgModule({
	imports : [ RouterModule.forChild(routes) ],
	exports : [ RouterModule ],
})
export class BlogRoutingModule {
}

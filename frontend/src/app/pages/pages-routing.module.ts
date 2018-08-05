import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";
import { AboutComponent } from "./about/about.component";
import { ContactComponent } from "./contact/contact.component";

const routes: Routes = [
	{
		path      : "contact",
		component : ContactComponent,
	}, {
		path      : "about-me",
		component : AboutComponent,
	},
];

@NgModule({
	imports : [ RouterModule.forChild(routes) ],
	exports : [ RouterModule ],
})
export class PagesRoutingModule {
}

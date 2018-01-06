import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { SessionComponent } from "./session.component";
import { LoginComponent } from "./login/login.component";

const routes: Routes = [
	{
		path      : "",
		component : SessionComponent,
		children  : [
			{ path : "login", component : LoginComponent },
		],
	},
];

@NgModule({
	imports : [ RouterModule.forChild(routes) ],
	exports : [ RouterModule ],
})
export class SessionRoutingModule {

}

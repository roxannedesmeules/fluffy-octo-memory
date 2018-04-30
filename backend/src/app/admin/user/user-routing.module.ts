import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import { UserComponent } from "admin/user/user.component";
import { ProfileComponent } from "admin/user/profile/profile.component";

import { MeResolve } from "@core/data/users";
import { LanguageResolve } from "@core/data/languages";

const routes: Routes = [
	{
		path      : "",
		component : UserComponent,
		children  : [
			{
				path      : "me",
				component : ProfileComponent,
				resolve   : {
					user      : MeResolve,
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
export class UserRoutingModule {
}

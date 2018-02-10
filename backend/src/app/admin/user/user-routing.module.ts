import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { UserComponent } from "@admin/user/user.component";
import { ProfileComponent } from "@admin/user/profile/profile.component";

import { MeResolve } from "@shared/resolver/user/me.resolve";

const RESOLVERS = [
	MeResolve,
];

const routes: Routes = [
	{
		path      : "me",
		component : UserComponent,
		children  : [
			{
				path      : "profile",
				component : ProfileComponent,
				resolve   : {
					user : MeResolve,
				},
			},
		],
	},
];

@NgModule({
	imports   : [ RouterModule.forChild(routes) ],
	exports   : [ RouterModule ],
	providers : [ ...RESOLVERS ],
})
export class UserRoutingModule {}

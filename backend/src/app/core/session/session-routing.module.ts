import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

//  Components
import { LoginComponent } from "./login/login.component";
import { LockScreenComponent } from "./lock-screen/lock-screen.component";

const routes: Routes = [
	{ path: "login", component: LoginComponent },
	{ path: "locked", component: LockScreenComponent },
];

@NgModule( {
	imports: [ RouterModule.forChild( routes ) ],
	exports: [ RouterModule ],
} )
export class SessionRoutingModule {
}

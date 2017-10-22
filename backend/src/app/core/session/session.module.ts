import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { SessionRoutingModule } from "./session-routing.module";

//  Login Component
import { LoginComponent } from "./login/login.component";

//  LockScreen Component
import { LockScreenComponent } from "./lock-screen/lock-screen.component";

//  Services
import { AuthService } from "services/user/auth.service";

@NgModule({
	imports      : [
		CommonModule,
		FormsModule,
		ReactiveFormsModule,

		SessionRoutingModule,
	],
	providers    : [
		AuthService,
	],
	declarations : [
		LoginComponent,
		LockScreenComponent,
	],
	exports      : [
		LoginComponent,
	],
})
export class SessionModule {
}

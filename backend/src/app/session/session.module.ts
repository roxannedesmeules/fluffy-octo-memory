import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ThemeModule } from "@theme/theme.module";

import { SessionRoutingModule } from "./session-routing.module";
import { SessionComponent } from "./session.component";
import { LoginComponent } from "./login/login.component";

@NgModule({
	imports      : [
		CommonModule,
		SessionRoutingModule,

		ThemeModule,
		FormsModule,
		ReactiveFormsModule,
	],
	declarations : [ SessionComponent, LoginComponent ],
	exports      : [ LoginComponent ],
})
export class SessionModule {
}

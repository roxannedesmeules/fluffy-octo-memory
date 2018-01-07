import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";

import { ThemeModule } from "../@theme/theme.module";
import { SessionRoutingModule } from "./session-routing.module";

import { LoginComponent } from "./login/login.component";
import { SessionComponent } from "./session.component";

@NgModule({
	imports      : [
		CommonModule,
		ThemeModule,

		FormsModule,
		ReactiveFormsModule,
		SessionRoutingModule,
	],
	declarations : [ LoginComponent, SessionComponent ],
	exports      : [ LoginComponent ],
})
export class SessionModule {}

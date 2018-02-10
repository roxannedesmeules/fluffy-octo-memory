import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ThemeModule } from "@theme/theme.module";
import { PipesModule } from "@shared/pipes/pipes.module";
import { UserRoutingModule } from "@admin/user/user-routing.module";

import { UserComponent } from "@admin/user/user.component";
import { ProfileComponent } from "./profile/profile.component";

@NgModule({
	imports      : [
		CommonModule,
		ThemeModule,
		UserRoutingModule,
		PipesModule,
	],
	declarations : [
		UserComponent,
		ProfileComponent,
	],
})
export class UserModule {
}

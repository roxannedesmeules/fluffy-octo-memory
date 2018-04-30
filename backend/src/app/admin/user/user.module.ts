import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";

import { UserRoutingModule } from "./user-routing.module";

import { PipesModule } from "@shared/pipes/pipes.module";
import { ThemeModule } from "@theme/theme.module";
import { FroalaEditorModule, FroalaViewModule } from "angular-froala-wysiwyg";

import { UserComponent } from "admin/user/user.component";
import { ProfileComponent } from './profile/profile.component';

@NgModule({
	imports      : [
		CommonModule,
		FormsModule,
		ReactiveFormsModule,
		UserRoutingModule,

		FroalaEditorModule,
		FroalaViewModule,

		PipesModule,
		ThemeModule,
	],
	declarations : [
		UserComponent,
		ProfileComponent,
	],
})
export class UserModule {
}

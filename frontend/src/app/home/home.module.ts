import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { DirectivesModule } from "../@shared/directives/directives.module";

import { HomeRoutingModule } from "./home-routing.module";
import { HomeComponent } from "./home.component";

@NgModule({
	imports      : [
		CommonModule,
		DirectivesModule,
		HomeRoutingModule,
	],
	declarations : [
		HomeComponent,
	],
})
export class HomeModule {
}

import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { DirectivesModule } from "../@shared/directives/directives.module";

import { HomeRoutingModule } from "./home-routing.module";
import { HomeComponent } from "./home.component";
import { FeaturedPostComponent } from './featured-post/featured-post.component';
import { LatestPostComponent } from './latest-post/latest-post.component';

@NgModule({
	imports      : [
		CommonModule,
		DirectivesModule,
		HomeRoutingModule,
	],
	declarations : [
		HomeComponent,
		FeaturedPostComponent,
		LatestPostComponent,
	],
})
export class HomeModule {
}

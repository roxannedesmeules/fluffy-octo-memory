import { BrowserModule } from "@angular/platform-browser";
import { NgModule } from "@angular/core";
import { ThemeModule } from "./@theme/theme.module";

import { AppRoutingModule } from "./app-routing.module";

import { AppComponent } from "./app.component";
import { BlogModule } from "./blog/blog.module";
import { HomeModule } from "./home/home.module";


@NgModule({
	declarations : [
		AppComponent,
	],
	imports      : [
		BrowserModule,
		AppRoutingModule,

		ThemeModule,
		HomeModule,
		BlogModule,
	],
	providers    : [],
	bootstrap    : [ AppComponent ],
})
export class AppModule {
}

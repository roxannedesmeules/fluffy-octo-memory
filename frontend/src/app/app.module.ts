import { APP_BASE_HREF } from "@angular/common";
import { HTTP_INTERCEPTORS } from "@angular/common/http";
import { BrowserModule } from "@angular/platform-browser";
import { NgModule } from "@angular/core";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { CoreModule } from "@core/core.module";
import { AuthInterceptor } from "@core/utils/auth.interceptor";
import { ThemeModule } from "./@theme/theme.module";

import { AppRoutingModule } from "./app-routing.module";

import { AppComponent } from "./app.component";
import { BlogModule } from "./blog/blog.module";
import { HomeModule } from "./home/home.module";

const BASE_MODULES = [
	BrowserModule,
	BrowserAnimationsModule,
];

const MODULES = [
	AppRoutingModule,

	ThemeModule.forRoot(),
	CoreModule.forRoot(),

	HomeModule,
	BlogModule,
];

const COMPONENTS = [
	AppComponent,
];

@NgModule({
	imports      : [ ... BASE_MODULES, ... MODULES ],
	declarations : [ ... COMPONENTS ],
	bootstrap    : [ ... COMPONENTS ],
	providers    : [
		{ provide : APP_BASE_HREF, useValue : "/" },
		{ provide : HTTP_INTERCEPTORS, useClass : AuthInterceptor, multi : true },
	],
})
export class AppModule {
}

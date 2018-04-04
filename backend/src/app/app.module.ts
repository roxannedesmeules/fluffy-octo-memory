import { NgModule } from "@angular/core";
import { BrowserModule } from "@angular/platform-browser";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";

import { CoreModule } from "@core/core.module";
import { ThemeModule } from "@theme/theme.module";
import { LoggerModule } from "./@shared/logger/logger.module";
import { AppRoutingModule } from "./app-routing.module";
import { AdminModule } from "admin/admin.module";
import { SessionModule } from "./session/session.module";

import { APP_BASE_HREF } from "@angular/common";
import { HTTP_INTERCEPTORS } from "@angular/common/http";
import { AuthInterceptor } from "@core/utils/auth.interceptor";

import { AppComponent } from "./app.component";

const BASE_MODULE = [
	BrowserModule,
	BrowserAnimationsModule,
];

const MODULES = [
	AppRoutingModule,

	ThemeModule.forRoot(),
	CoreModule.forRoot(),

	SessionModule,
	AdminModule,

	LoggerModule.forRoot(),
];

const COMPONENTS = [
	AppComponent,
];


@NgModule({
	imports      : [ ...BASE_MODULE, ...MODULES ],
	declarations : [ ...COMPONENTS ],
	providers    : [
		{ provide : APP_BASE_HREF, useValue : "/" },
		{ provide : HTTP_INTERCEPTORS, useClass : AuthInterceptor, multi : true },
	],
	bootstrap    : [ AppComponent ],
})
export class AppModule {
}

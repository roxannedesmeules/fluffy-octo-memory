import { NgModule } from "@angular/core";
import { BrowserModule } from "@angular/platform-browser";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { LoadingBarHttpClientModule } from '@ngx-loading-bar/http-client';

import { CoreModule } from "@core/core.module";
import { ThemeModule } from "@theme/theme.module";
import { LoggerModule } from "@shared/logger/logger.module";
import { NgxSpinnerModule } from "ngx-spinner";
import { AppRoutingModule } from "./app-routing.module";
import { AdminModule } from "./admin/admin.module";
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
	LoadingBarHttpClientModule,
	NgxSpinnerModule,
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

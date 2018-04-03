import { BrowserModule } from "@angular/platform-browser";
import { NgModule } from "@angular/core";

import { CoreModule } from "@core/core.module";
import { ThemeModule } from "@theme/theme.module";
import { AppRoutingModule } from "./app-routing.module";

import { APP_BASE_HREF } from "@angular/common";
import { HTTP_INTERCEPTORS } from "@angular/common/http";
import { AuthInterceptor } from "@core/utils/auth.interceptor";

import { AppComponent } from "./app.component";
import { SessionModule } from "./session/session.module";

const BASE_MODULE = [
	BrowserModule,
];

const MODULES = [
	AppRoutingModule,

	ThemeModule.forRoot(),
	CoreModule.forRoot(),

	SessionModule,
];

const COMPONENTS = [
	AppComponent,
];


@NgModule({
	declarations : [ ...COMPONENTS ],
	imports      : [ ...BASE_MODULE, ...MODULES ],
	providers    : [
		{ provide : APP_BASE_HREF, useValue : "/" },
		{ provide : HTTP_INTERCEPTORS, useClass : AuthInterceptor, multi : true },
	],
	bootstrap    : [ AppComponent ],
})
export class AppModule {
}

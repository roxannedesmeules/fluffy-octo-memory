// angular core
import { BrowserModule } from "@angular/platform-browser";
import { NgModule } from "@angular/core";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { HTTP_INTERCEPTORS, HttpClientModule } from "@angular/common/http";

// third party modules
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";
import { ToasterModule } from "angular2-toaster";

// application modules
import { AppRoutingModule } from "./app-routing.module";
import { ThemeModule } from "@theme/theme.module";
import { CoreModule } from "@core/core.module";

// application components
import { AppComponent } from "./app.component";

// application widgets

// providers
import { APP_BASE_HREF } from "@angular/common";
import { HttpExInterceptor } from "@core/utils/http-ex.interceptor";

@NgModule({
	bootstrap    : [ AppComponent ],
	imports      : [
		BrowserModule,
		BrowserAnimationsModule,
		HttpClientModule,
		FormsModule,
		ReactiveFormsModule,

		AppRoutingModule,

		ToasterModule,

		NgbModule.forRoot(),
		ThemeModule.forRoot(),
		CoreModule.forRoot(),
	],
	declarations : [ AppComponent ],
	providers    : [
		{ provide : HTTP_INTERCEPTORS, useClass : HttpExInterceptor, multi : true },
		{ provide : APP_BASE_HREF, useValue : "/" },
	],
})
export class AppModule {
}

// angular core
import { BrowserModule } from "@angular/platform-browser";
import { NgModule } from "@angular/core";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { HttpModule } from "@angular/http";

// third party modules
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";

// application modules
import { AppRoutingModule } from "./app-routing.module";
import { ThemeModule } from "./@theme/theme.module";
import { CoreModule } from "./@core/core.module";

// application components
import { AppComponent } from "./app.component";

// application widgets

// providers
import { APP_BASE_HREF } from "@angular/common";

@NgModule({
	bootstrap    : [ AppComponent ],
	imports      : [
		BrowserModule,
		BrowserAnimationsModule,
		HttpModule,
		AppRoutingModule,

		NgbModule.forRoot(),
		ThemeModule.forRoot(),
		CoreModule.forRoot(),
	],
	declarations : [ AppComponent ],
	providers    : [
		{ provide : APP_BASE_HREF, useValue : "/" },
	],
})
export class AppModule {
}

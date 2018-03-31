import { BrowserModule } from "@angular/platform-browser";
import { NgModule } from "@angular/core";
import { ThemeModule } from "./@theme/theme.module";

import { AppRoutingModule } from "./app-routing.module";

import { AppComponent } from "./app.component";

const BASE_MODULE = [
	BrowserModule,
];

const MODULES = [
	AppRoutingModule,

	ThemeModule.forRoot(),
];

const COMPONENTS = [
	AppComponent,
];


@NgModule({
	declarations : [ ...COMPONENTS ],
	imports      : [ ...BASE_MODULE, ...MODULES ],
	providers    : [],
	bootstrap    : [ AppComponent ],
})
export class AppModule {
}

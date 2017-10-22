//  Angular Core
import { BrowserModule } from "@angular/platform-browser";
import { NgModule } from "@angular/core";
import { HttpModule } from "@angular/http";

//  Library Modules
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";

//  Application Modules
import { AppRoutingModule } from "./app-routing.module";
import { CoreModule } from "./core/core.module";
import { ComponentsModule } from "./components/components.module";

//  Application components
import { AppComponent } from "./app.component";

//  Widgets
import { LoggerModule } from "./shared/widgets/logger/logger.module";

//  Providers
import { HttpExProvider } from "./shared/helpers/http-ex";

@NgModule({
	imports      : [
		//  angular
		BrowserModule,
		HttpModule,

		//  library
		NgbModule.forRoot(),

		//  application modules
		AppRoutingModule,
		CoreModule,
		ComponentsModule,

		//  Widgets
		LoggerModule,
	],
	declarations : [
		AppComponent,
	],
	providers    : [
		HttpExProvider,
	],
	bootstrap    : [ AppComponent ],
})
export class AppModule {
}

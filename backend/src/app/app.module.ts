//  Angular Core
import { BrowserModule } from "@angular/platform-browser";
import { NgModule } from "@angular/core";
import { HttpModule } from "@angular/http";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";

//  Library Modules
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";

//  Application Modules
import { AppRoutingModule } from "./app-routing.module";
import { CoreModule } from "./core/core.module";
import { ComponentsModule } from "./components/components.module";

//  Application components
import { AppComponent } from "./app.component";

//  Widgets
import { LoggerModule } from "./widgets/logger/logger.module";

//  Providers
import { HttpExProvider } from "./shared/helpers/http-ex";
import { UserService } from "services/user/user.service";
import { AuthService } from "services/user/auth.service";
import { AuthGuard } from "./shared/helpers/guards/auth.guard";
import { LangService } from "services/lang/lang.service";

@NgModule({
	imports      : [
		//  angular
		BrowserModule,
		BrowserAnimationsModule,
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
		UserService,
		AuthService,
		AuthGuard,
		LangService,
	],
	bootstrap    : [ AppComponent ],
})
export class AppModule {
}

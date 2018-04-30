import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { RouterModule } from "@angular/router";
import { PageTitleService } from "@theme/components/page-title/page-title.service";

import { NgbModule } from "@ng-bootstrap/ng-bootstrap";
import { LaddaModule } from "angular2-ladda";

import {
	FooterComponent, HeaderComponent, ItemComponent, ItemDropdownComponent, ItemLinkComponent, PageTitleComponent,
	SidemenuComponent,
} from "./components";
import { LayoutComponent } from "./layout/layout.component";
import { MessagesComponent, NotificationsComponent, UserComponent } from "./widgets";

const MODULES = [
	//  core modules
	CommonModule,
	RouterModule,

	// third party modules
	NgbModule,
	LaddaModule,
];

const COMPONENTS = [
	LayoutComponent,

	//  components
	FooterComponent,
	HeaderComponent,
	PageTitleComponent,
	SidemenuComponent,
	ItemComponent,
	ItemDropdownComponent,
	ItemLinkComponent,

	//  widgets
	MessagesComponent,
	NotificationsComponent,
	UserComponent,
];

const PROVIDERS = [
	PageTitleService,
];

@NgModule({
	imports      : [ ...MODULES ],
	declarations : [ ...COMPONENTS ],
	exports      : [ ...MODULES, ...COMPONENTS ],
})
export class ThemeModule {
	static forRoot (): ModuleWithProviders {
		return <ModuleWithProviders>{
			ngModule  : ThemeModule,
			providers : [ ...PROVIDERS ],
		};
	}
}

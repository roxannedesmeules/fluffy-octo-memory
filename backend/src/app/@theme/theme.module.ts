import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { NgbDropdownConfig, NgbModule } from "@ng-bootstrap/ng-bootstrap";
import {
	FooterComponent, HeaderComponent, ItemComponent, ItemDropdownComponent, ItemLinkComponent, PageTitleComponent,
	SidemenuComponent,
} from "./components";
import { LayoutComponent } from "./layout/layout.component";
import { MessagesComponent, NotificationsComponent, UserComponent } from "./widgets";

const MODULES = [
	CommonModule,
	NgbModule,
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
	NgbDropdownConfig,
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

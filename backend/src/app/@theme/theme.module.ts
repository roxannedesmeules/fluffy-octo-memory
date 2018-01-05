import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";

import {
	NbActionsModule,
	NbCardModule,
	NbLayoutModule,
	NbMenuModule,
	NbRouteTabsetModule,
	NbSearchModule,
	NbSidebarModule,
	NbTabsetModule,
	NbThemeModule,
	NbUserModule,
	NbCheckboxModule,
} from "@nebular/theme";

import {
	FooterComponent,
	HeaderComponent,
	SearchInputComponent,
	ThemeSettingsComponent,
	TinyMCEComponent,
} from "./components";
import { CapitalizePipe, PluralPipe, RoundPipe, TimingPipe } from "./pipes";
import { OneColumnLayoutComponent } from "./layouts";

const BASE_MODULES = [ CommonModule, FormsModule, ReactiveFormsModule ];

const NB_MODULES = [
	NbCardModule,
	NbLayoutModule,
	NbTabsetModule,
	NbRouteTabsetModule,
	NbMenuModule,
	NbUserModule,
	NbActionsModule,
	NbSearchModule,
	NbSidebarModule,
	NbCheckboxModule,
	NgbModule,
];

const COMPONENTS = [
	HeaderComponent,
	FooterComponent,
	SearchInputComponent,
	ThemeSettingsComponent,
	TinyMCEComponent,
	OneColumnLayoutComponent,
];

const PIPES = [
	CapitalizePipe,
	PluralPipe,
	RoundPipe,
	TimingPipe,
];

const NB_THEME_PROVIDERS = [
	... NbThemeModule.forRoot({ name : "default", }).providers,
	... NbSidebarModule.forRoot().providers,
	... NbMenuModule.forRoot().providers,
];

@NgModule({
	imports      : [ ... BASE_MODULES, ... NB_MODULES ],
	exports      : [ ... BASE_MODULES, ... NB_MODULES, ... COMPONENTS, ... PIPES ],
	declarations : [ ... COMPONENTS, ... PIPES ],
})
export class ThemeModule {
	static forRoot (): ModuleWithProviders {
		return <ModuleWithProviders>{
			ngModule  : ThemeModule,
			providers : [ ... NB_THEME_PROVIDERS ],
		};
	}
}

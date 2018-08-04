import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { RouterModule } from "@angular/router";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";
import { DirectivesModule } from "@shared/directives/directives.module";

import { FooterComponent, HeaderComponent } from "./components";
import { BlogComponent, HomeComponent, AppComponent } from "./layout";
import { CategoriesComponent, SearchComponent, TagsComponent } from "./widgets";


const BASE_MODULES = [
	CommonModule,
	RouterModule,
	BrowserAnimationsModule,
	FormsModule,
	ReactiveFormsModule,
];

const MODULES = [
	NgbModule,
	DirectivesModule,
];

const COMPONENTS = [
	//  components
	FooterComponent,
	HeaderComponent,

	//  Layouts
	AppComponent,
	HomeComponent,
	BlogComponent,

	//  widgets
	CategoriesComponent,
	SearchComponent,
	TagsComponent,
];

const PROVIDERS = [];

@NgModule({
	imports      : [ ...BASE_MODULES, ...MODULES ],
	declarations : [ ...COMPONENTS ],
	exports      : [ ...COMPONENTS ],
})
export class ThemeModule {
	static forRoot (): ModuleWithProviders {
		return <ModuleWithProviders>{
			ngModule  : ThemeModule,
			providers : [ ...PROVIDERS ],
		};
	}
}


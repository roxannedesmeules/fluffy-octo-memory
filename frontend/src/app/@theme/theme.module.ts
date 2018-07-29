import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { RouterModule } from "@angular/router";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";

import { FooterComponent, HeaderComponent } from "./components";
import { BlogComponent, HomeComponent } from "./layout";
import { CategoriesComponent, SearchComponent, TagsComponent } from "./widgets";


const BASE_MODULES = [
	CommonModule,
	RouterModule,
	FormsModule,
	ReactiveFormsModule,
	NgbModule
];

const COMPONENTS = [
	//  components
	FooterComponent,
	HeaderComponent,

	HomeComponent,
	BlogComponent,

	//  widgets
	CategoriesComponent,
	SearchComponent,
	TagsComponent,
];

const PROVIDERS = [];

@NgModule({
	imports      : [ ...BASE_MODULES ],
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


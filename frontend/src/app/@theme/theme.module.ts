import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ReactiveFormsModule } from "@angular/forms";
import { RouterModule } from "@angular/router";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";

import { FooterComponent, HeaderComponent } from "./components";
import { TopCategoriesComponent, TopTagsComponent } from "./widgets";
import { LayoutComponent } from './layout/layout.component';


const BASE_MODULES = [
	CommonModule,
	RouterModule,
	ReactiveFormsModule,
	NgbModule
];

const COMPONENTS = [
	//  components
	FooterComponent,
	HeaderComponent,
	LayoutComponent,

	//  widgets
	TopCategoriesComponent,
	TopTagsComponent,
];

@NgModule({
	imports      : [ ...BASE_MODULES ],
	declarations : [ ...COMPONENTS ],
	exports      : [ ...COMPONENTS ],
})
export class ThemeModule {
}


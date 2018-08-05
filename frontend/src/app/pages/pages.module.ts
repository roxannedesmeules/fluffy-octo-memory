import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { PipesModule } from "@shared/pipes/pipes.module";
import { LaddaModule } from "angular2-ladda";

import { PagesRoutingModule } from "./pages-routing.module";

import { ContactComponent } from "./contact/contact.component";
import { AboutComponent } from './about/about.component';

const BASE_MODULES = [
	CommonModule,
	FormsModule,
	ReactiveFormsModule,
];

const MODULES = [
	PagesRoutingModule,

	PipesModule,
	LaddaModule,
];

const COMPONENTS = [
	ContactComponent,
	AboutComponent
];

@NgModule({
	imports      : [ ...BASE_MODULES, ...MODULES ],
	declarations : [ ...COMPONENTS ],
})
export class PagesModule {
}

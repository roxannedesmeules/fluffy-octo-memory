import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { LaddaModule } from "angular2-ladda";

import { ContactRoutingModule } from "./contact-routing.module";
import { ContactComponent } from './contact.component';

const MODULES = [
	CommonModule,
	ContactRoutingModule,

	FormsModule,
	ReactiveFormsModule,

	LaddaModule,
];

const COMPONENTS = [
	ContactComponent,
];

@NgModule({
	imports      : [ ...MODULES ],
	declarations : [ ...COMPONENTS ],
})
export class ContactModule {
}

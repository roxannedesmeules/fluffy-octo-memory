import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ReactiveFormsModule } from "@angular/forms";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";
import { FooterComponent, HeaderComponent } from "./components";

const BASE_MODULES = [ CommonModule, ReactiveFormsModule, NgbModule ];

const COMPONENTS = [
	HeaderComponent,
	FooterComponent,
];

@NgModule({
	imports      : [ ...BASE_MODULES ],
	declarations : [ ...COMPONENTS ],
	exports      : [ ...COMPONENTS ],
})
export class ThemeModule {
}


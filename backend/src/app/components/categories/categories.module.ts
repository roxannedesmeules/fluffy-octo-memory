import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";

import { CategoriesRoutingModule } from "components/categories/categories-routing.module";

import { ListComponent } from "./list/list.component";
import { DetailsComponent } from './details/details.component';

import { CategoriesService } from "services/categories/categories.service";
import { PipeModule } from "../../pipes/pipe.module";

@NgModule({
	imports      : [
		CommonModule,
		FormsModule,
		ReactiveFormsModule,

		NgbModule,

		CategoriesRoutingModule,
		PipeModule,
	],
	declarations : [
		ListComponent,
		DetailsComponent,
	],
	providers    : [
		CategoriesService,
	],
})
export class CategoriesModule {}

import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { ThemeModule } from "@theme/theme.module";

import { CommunicationRoutingModule } from "./communication-routing.module";
import { PipesModule } from "@shared/pipes/pipes.module";

import { CommunicationComponent } from "./communication.component";
import { DetailComponent } from "./detail/detail.component";
import { ListComponent } from "./list/list.component";

const COMPONENTS = [
	CommunicationComponent,
	ListComponent,
	DetailComponent,
];

@NgModule({
	imports      : [
		CommonModule,
		CommunicationRoutingModule,

		ThemeModule,
		PipesModule,
	],
	declarations : [ ...COMPONENTS ],
})
export class CommunicationModule {
}

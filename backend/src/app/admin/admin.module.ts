import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { CoreModule } from "@core/core.module";
import { NgbModule } from "@ng-bootstrap/ng-bootstrap";
import { ThemeModule } from "@theme/theme.module";

import { AdminRoutingModule } from "./admin-routing.module";
import { AdminComponent } from "./admin.component";
import { DashboardComponent } from './dashboard/dashboard.component';

@NgModule({
	imports      : [
		CommonModule,
		AdminRoutingModule,

		CoreModule,
		ThemeModule,

		NgbModule.forRoot(),
	],
	declarations : [ AdminComponent, DashboardComponent ],
})
export class AdminModule {
}

import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { CoreModule } from "@core/core.module";
import { ThemeModule } from "@theme/theme.module";

import { AdminRoutingModule } from "./admin-routing.module";
import { AdminComponent } from "./admin.component";
import { DashboardComponent } from './dashboard/dashboard.component';

@NgModule({
	imports      : [
		CommonModule,
		CoreModule,
		ThemeModule,
		AdminRoutingModule,
	],
	declarations : [ AdminComponent, DashboardComponent ],
})
export class AdminModule {
}

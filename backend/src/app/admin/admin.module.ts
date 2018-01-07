import { NgModule } from "@angular/core";

import { AdminComponent } from "@admin/admin.component";
import { DashboardModule } from "@admin/dashboard/dashboard.module";
import { AdminRoutingModule } from "@admin/admin-routing.module";
import { ThemeModule } from "@theme/theme.module";

const ADMIN_COMPONENTS = [
	AdminComponent,
];

@NgModule({
	imports      : [
		AdminRoutingModule,
		ThemeModule,
		DashboardModule,
	],
	declarations : [
		... ADMIN_COMPONENTS,
	],
})
export class AdminModule {
}

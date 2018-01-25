import { NgModule } from "@angular/core";
import { ThemeModule } from "@theme/theme.module";

import { FilterComponent } from "@shared/widgets/filter/filter.component";
import { PaginationComponent } from "./pagination/pagination.component";

const WIDGETS = [
	FilterComponent,
	PaginationComponent,
];

@NgModule({
	imports      : [
		ThemeModule,
	],
	declarations : [ ... WIDGETS ],
	exports      : [ ... WIDGETS ],
})
export class WidgetsModule {}

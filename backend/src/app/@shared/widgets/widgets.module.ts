import { NgModule } from "@angular/core";
import { ThemeModule } from "@theme/theme.module";

import { FilterComponent } from "@shared/widgets/filter/filter.component";

const WIDGETS = [
	FilterComponent,
];

@NgModule({
	imports      : [
		ThemeModule,
	],
	declarations : [ ... WIDGETS ],
	exports      : [ ... WIDGETS ],
})
export class WidgetsModule {}

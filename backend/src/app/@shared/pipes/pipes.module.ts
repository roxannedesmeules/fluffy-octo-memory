import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { AtIndexOfPipe } from "./array/at-index-of.pipe";
import { ColumnPipe } from "./array/column.pipe";
import { SlugPipe } from "./string/slug.pipe";
import { VerbalBooleanPipe } from "./string/verbal-boolean.pipe";

const SHARED_PIPES = [
	AtIndexOfPipe,
	ColumnPipe,
	SlugPipe,
	VerbalBooleanPipe,
];

@NgModule({
	imports      : [
		CommonModule,
	],
	declarations : [ ... SHARED_PIPES ],
	exports      : [ ... SHARED_PIPES ],
	providers    : [ ... SHARED_PIPES ],
})
export class PipesModule {}

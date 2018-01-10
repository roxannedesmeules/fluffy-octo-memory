import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { AtIndexOfPipe } from "./at-index-of.pipe";
import { ColumnPipe } from "./column.pipe";
import { SlugPipe } from "./slug.pipe";
import { VerbalBooleanPipe } from "./verbal-boolean.pipe";

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

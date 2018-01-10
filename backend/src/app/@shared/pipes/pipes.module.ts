import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { AtIndexOfPipe } from "./at-index-of.pipe";
import { SlugPipe } from "./slug.pipe";

const SHARED_PIPES = [
	AtIndexOfPipe,
	SlugPipe,
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

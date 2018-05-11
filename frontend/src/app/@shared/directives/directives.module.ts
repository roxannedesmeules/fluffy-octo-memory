import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { MatchHeightDirective } from "./match-height/match-height.directive";

const DIRECTIVES = [
	MatchHeightDirective,
];

@NgModule({
	imports      : [
		CommonModule,
	],
	declarations : [ ... DIRECTIVES ],
	exports      : [ ... DIRECTIVES ],
})
export class DirectivesModule {
}

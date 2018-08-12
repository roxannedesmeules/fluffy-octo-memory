import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { AutofocusDirective } from "./autofocus/autofocus.directive";
import { MatchHeightDirective } from "./match-height/match-height.directive";

const DIRECTIVES = [
    MatchHeightDirective,
    AutofocusDirective,
];

@NgModule({
    imports      : [
        CommonModule,
    ],
    declarations : [ ...DIRECTIVES ],
    exports      : [ ...DIRECTIVES ],
})
export class DirectivesModule {
}

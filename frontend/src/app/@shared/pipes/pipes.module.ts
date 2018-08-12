import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
// import { TimeAgoPipe } from "time-ago-pipe";
import { SafeHtmlPipe } from "./string/safe-html.pipe";

const PIPES = [
    // third party pipes
    // TimeAgoPipe,

    // custom pipes
    SafeHtmlPipe,
];

@NgModule({
    imports      : [
        CommonModule,
    ],
    declarations : [ ...PIPES ],
    exports      : [ ...PIPES ],
    providers    : [ ...PIPES ],
})
export class PipesModule {
}

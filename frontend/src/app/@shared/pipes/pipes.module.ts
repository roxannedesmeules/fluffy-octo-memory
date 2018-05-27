import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { SafeHtmlPipe } from "./string/safe-html.pipe";

const PIPES = [
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

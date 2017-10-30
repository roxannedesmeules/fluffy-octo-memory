import { Component } from "@angular/core";
import { fadeInAnimation } from "../../../shared/helpers/animations/fade-in.animation";

@Component({
	selector    : "layout-regular-admin",
	templateUrl : "./regular-admin-page.component.html",
	styleUrls   : [ "./regular-admin-page.component.scss" ],
	animations  : [ fadeInAnimation ],
	host        : { "[@fadeInAnimation]" : "" },
})
export class RegularAdminPageComponent {
	constructor () { }
}
